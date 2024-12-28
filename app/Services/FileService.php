<?php

namespace App\Services;

use App\Models\File;
use App\Models\FileGroup;
use App\Models\FileLog;
use Illuminate\Http\Request;
use App\Traits\FileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Psy\Command\WhereamiCommand;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;
use ZipArchive;

class FileService extends BaseService
{
    use FileTrait;

    public function __construct(File $model)
    {
        parent::__construct($model);
    }

    public function uploadOrModify(Request $request, $fileId = null)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $disk = 'public';
            $filePath = $this->uploadFile($disk, $file);
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            if ($fileId) {
                return $this->modifyExistingFile($request, $fileId, $filePath, $file);
            } else {
                $newFile = $this->createNewFile($request, $filePath,$fileName,$fileSize);
                    $this->createNewFileGroup($request, $newFile->id);
            }
            return  $newFile;
        }

    }

    private function modifyExistingFile(Request $request, $fileId, $filePath, $file)
    {
        $existingFile = File::find($fileId);
        if ($existingFile) {
            $oldContent = $this->getFileContent($existingFile->path);
            $newContent = file_get_contents($file->getRealPath());
            $diff = $this->getFileDiff($oldContent, $newContent);

            $this->logFileChanges($request, $fileId, $diff);

            $existingFile->path = $filePath;
            $existingFile->save();
        }
        return  $existingFile;
    }

    private function createNewFile(Request $request, $filePath,$name,$fileSize)
    {
       return  File::create([
            'user_id' => $request->user()->id,
            'path' => $filePath,
            'name' => $name,
            'size' => $fileSize,
            'status' => false,
        ]);

    }

    private function createNewFileGroup($request,$fileId)
    {
        FileGroup::create([
            'file_id' => $fileId,
            'group_id' => $request->group_id,

        ]);
    }

    private function getFileContent($path)
    {
        $disk = 'public';
        return Storage::disk($disk)->exists($path) ? Storage::disk($disk)->get($path) : '';
    }

    private function getFileDiff($oldContent, $newContent)
    {
        $oldLines = explode("\n", $oldContent);
        $newLines = explode("\n", $newContent);

        $added = $this->getAddedLines($oldLines, $newLines);
        $removed = $this->getRemovedLines($oldLines, $newLines);

        return [
            'added' => $added,
            'removed' => $removed,
        ];
    }

    private function getAddedLines($oldLines, $newLines)
    {
        $added = [];
        foreach ($newLines as $newLine) {
            if (!in_array($newLine, $oldLines)) {
                $added[] = trim($newLine);
            }
        }
        return array_values(array_filter($added));
    }

    private function getRemovedLines($oldLines, $newLines)
    {
        $removed = [];
        foreach ($oldLines as $oldLine) {
            if (!in_array($oldLine, $newLines)) {
                $removed[] = trim($oldLine);
            }
        }
        return array_values(array_filter($removed));
    }

    private function logFileChanges(Request $request, $fileId, $diff)
    {
        if (!empty($diff['added']) || !empty($diff['removed'])) {
            FileLog::create([
                'user_id' => $request->user()->id,
                'file_id' => $fileId,
                'operation' => 'modified',
                'file' => json_encode($diff, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'date' => now(),
            ]);
        }
    }
    public function downloadMultipleFiles(Request $request, $fileIds)
    {
        $ids = array_filter(explode(',', $fileIds), 'is_numeric');


        $files = File::findMany($ids);



        $zip = new ZipArchive();
        $zipFileName = 'files_' . now()->format('Y-m-d_His') . '.zip';
        $zipPath = realpath(storage_path('app')) . DIRECTORY_SEPARATOR . $zipFileName;


        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return response()->json(['error' => 'Cannot create zip file.'], 500);
        }

        foreach ($files as $file) {
            $filePath = storage_path('app' . $file->path);
            if (!file_exists($filePath)) {
                continue;
            }
            $zip->addFile($filePath, basename($file->path));
            $this->logOperation($file->id,'download');
        }

        if (!$zip->close()) {
            return response()->json(['error' => 'Failed to close ZIP file.'], 500);
        }

        if (!file_exists($zipPath)) {
            return response()->json(['error' => 'ZIP file was not created.'], 500);
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }






    public function logOperation($fileId,$operation)
    {
      return  FileLog::create([
            'user_id' => Auth::user()->id,
            'file_id' => $fileId,
            'operation' => $operation,
            'file' => null,
            'date' => now(),
        ]);
    }

    private function isFileLockedByAnotherUser($file, Request $request)
    {
        $lastLog = FileLog::where('file_id', $file->id)->orderBy('created_at', 'desc')->first();
        return $lastLog && $lastLog->operation === 'upload' && $file->locked_by !== $request->user()->id;
    }


    public static function report($fileId, $from, $to)
    {
        $file = File::with(['fileLogs' => function ($query) use ($from, $to) {
            $query->dateBetween($from, $to);
        }])->findOrFail($fileId);

        return $file;
    }


}
