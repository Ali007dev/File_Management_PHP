<?php

namespace App\Services;

use App\Models\File;
use App\Models\FileGroup;
use SebastianBergmann\Diff\Differ;

use App\Models\FileLog;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;
use App\Traits\FileTrait;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Psy\Command\WhereamiCommand;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use ZipArchive;

class FileService extends BaseService
{
    use FileTrait;

    public function __construct(FileRepository $repository)
    {
        parent::__construct($repository);
    }

    public function uploadOrModify(Request $request, $fileId = null)
    {
        $fileId = $request['fileId'];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $disk = 'public';
            $filePath = $this->uploadFile($disk, $file);
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            if ($fileId && $request['group_id']) {

                $newFile =   $this->modifyExistingFile($request, $fileId, $filePath, $file);
                app(NotificationService::class)->sendNotification('modify', $request['group_id']);
            } else {
                $newFile = $this->createNewFile($request, $filePath, $fileName, $fileSize);
                $this->createNewFileGroup($request, $newFile->id);
                app(NotificationService::class)->sendNotification('add', $request->group_id);
            }
            return  $newFile;
        }
    }

    public function modifyExistingFile($request, $fileId, $filePath, $file)
    {
        $existingFile = File::find($fileId);
        if ($existingFile) {
            $oldContent = $this->getFileContent($existingFile->path);
            $newContent = file_get_contents($file->getRealPath());
            $diff = $this->getFileDiff($oldContent, $newContent);


            $this->logFileChanges($request, $fileId, $diff);

            $existingFile->path = $filePath;
            $existingFile->save();
            $this->lockFile($existingFile, 0, null);
        }
        return  $existingFile;
    }

    public function createNewFile($request, $filePath, $name, $fileSize)
    {
        return  File::create([
            'user_id' => $request->user()->id,
            'path' => $filePath,
            'name' => $name,
            'size' => $fileSize,
            'status' => false,
        ]);
    }

    public function createNewFileGroup($request, $fileId)
    {
        FileGroup::create([
            'file_id' => $fileId,
            'group_id' => $request->group_id,
        ]);
    }

    public function getById($id)
    {
        return  FileLog::findOrFail($id);
    }


    public function compare($currentId, $oldId)
    {
        $old = $this->getById($oldId);
        $currentPath = File::where('id', $currentId)->value('path');
        $current = Storage::disk('public')->get($currentPath);
        $oldContent = $old->file['old'];
        $diff = $this->getFileDiff($oldContent, $current);
        return $diff;
    }

    public function createDiffPdf($diffResults)
    {
        $html = View::make('diff_report', ['diffResults' => $diffResults])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        file_put_contents("path/to/save/diffReport.pdf", $output);
    }



    public function getFileContent($path)
    {
        $file = Storage::get($path);
        return $file;
    }


    public function getFileDiff($oldContent, $newContent)
    {
        $oldLines = explode("\n", $oldContent);
        $newLines = explode("\n", $newContent);

        $builder = new StrictUnifiedDiffOutputBuilder([
            'contextLines' => 0,
            'fromFile'     => 'Original',
            'toFile'       => 'New',
        ]);

        $differ = new Differ($builder);
        $diff = $differ->diff($oldLines, $newLines);

        $lines = explode("\n", $diff);
        $added = [];
        $removed = [];

        foreach ($lines as $line) {
            if (strpos($line, '+') === 0 && !str_starts_with($line, '+++')) {
                $added[] = substr($line, 1);
            } elseif (strpos($line, '-') === 0 && !str_starts_with($line, '---')) {
                $removed[] = substr($line, 1);
            }
        }

        return [
            'added' => $added,
            'old' => $oldContent,
            'removed' => $removed,
        ];
    }

    public function getAddedLines($oldLines, $newLines)
    {
        $added = [];
        foreach ($newLines as $newLine) {
            if (!in_array($newLine, $oldLines)) {
                $added[] = trim($newLine);
            }
        }
        return array_values(array_filter($added));
    }

    public function getRemovedLines($oldLines, $newLines)
    {
        $removed = [];
        foreach ($oldLines as $oldLine) {
            if (!in_array($oldLine, $newLines)) {
                $removed[] = trim($oldLine);
            }
        }
        return array_values(array_filter($removed));
    }

    public function logFileChanges($request, $fileId, $diff)
    {
        FileLog::create([
            'user_id' => $request->user()->id,
            'file_id' => $fileId,
            'operation' => 'modified',
            'file' => json_encode($diff, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'date' => now(),
        ]);
    }
    public function downloadMultipleFiles(Request $request, $fileIds)
    {
        $ids = array_filter(explode(',', $fileIds), 'is_numeric');


        $files = File::findMany($ids);



        $zip = new ZipArchive();
        $zipFileName = 'files_' . now()->format('Y-m-d_His') . '.zip';
        $zipPath = realpath(storage_path('app')) . DIRECTORY_SEPARATOR . $zipFileName;


        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return response()->json(['message' => 'Cannot create zip file.'], 500);
        }

        foreach ($files as $file) {

            if (!$this->isFileLocked($file->id)) {
                return response()->json(['message' => 'Cannot Download File Becuse is Locked.'], 500);
            }
            $filePath = storage_path('app' . $file->path);

            if (!file_exists($filePath)) {
                continue;
            }
           // app(NotificationService::class)->sendNotification('download', $request->group_id);

            $zip->addFile($filePath, basename($file->name));
            $this->lockFile($file, 1, Auth::user()->id);
            $this->logOperation($file->id, 'download');
        }

        if (!$zip->close()) {
            return response()->json(['message' => 'Failed to close ZIP file.'], 500);
        }

        if (!file_exists($zipPath)) {
            return response()->json(['message' => 'ZIP file was not created.'], 500);
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }






    public function logOperation($fileId, $operation)
    {
        return  FileLog::create([
            'user_id' => Auth::user()->id,
            'file_id' => $fileId,
            'operation' => $operation,
            'file' => null,
            'date' => now(),
        ]);
    }

    private function isFileLockedByAnotherUser($file, $request)
    {
        $lastLog = FileLog::where('file_id', $file->id)->orderBy('created_at', 'desc')->first();
        return $lastLog && $lastLog->operation === 'upload' && $file->locked_by !== $request->user()->id;
    }

    private function isFileLocked($file)
    {
        $file = File::findOrFail($file);
        if ($file->locked_by != null) {
            return false;
        } else {
            return true;
        }
    }

    private function lockFile($file, $status, $locked)
    {
        $file->locked_by = $locked;
        $file->status = $status;
        $file->save();
    }



    public static function report($fileId, $from, $to)
    {
        $file = File::with('fileLogs'
       )->findOrFail($fileId);

        return $file;
    }


    public static function getArchive($request, $fileId)
    {
        $file = File::with('archive')->findOrFail($fileId);

        return $file;
    }
}
