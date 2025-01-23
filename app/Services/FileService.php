<?php

namespace App\Services;

use App\Events\TransactionEvent;
use App\Helpers\FileParser;
use App\Helpers\PdfHelper;
use App\Jobs\CreateNewFileJob;
use App\Jobs\LogFileChangesJob;
use App\Models\File;
use App\Models\FileGroup;
use SebastianBergmann\Diff\Differ;

use App\Models\FileLog;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;
use App\Traits\FileTrait;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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


            $this->logFileChanges($request, $fileId, $diff, 'modified');

            $existingFile->path = $filePath;
            $existingFile->save();
            $this->lockFile($existingFile, 0, null);
        }
        return  $existingFile;
    }

    public function createNewFile($request, $filePath, $name, $fileSize)
    {
        $userId = $request->user()->id;
        return  CreateNewFileJob::dispatch($userId, $filePath, $name, $fileSize, $request['group_id']);
    }


    public function compare($oldId)
    {
        return app(PdfHelper::class)->compare($oldId);
    }

    public function archive($oldId)
    {
        return app(PdfHelper::class)->archive($oldId);
    }



    public function getFileContent($path)
    {
        $file = Storage::get($path);
        return $file;
    }

    private function getFileDiff($oldContent, $newContent)
    {
        return FileParser::getFileDiff($oldContent, $newContent);
    }




    public function logFileChanges($request, $fileId, $diff, $operation)
    {
        $userId = $request->user()->id;

        return LogFileChangesJob::dispatch($userId, $fileId, $operation, $diff);
    }
    public function downloadMultipleFiles(Request $request, $fileIds)
    {
        return DB::transaction(function () use ($request, $fileIds) {
            $ids = array_filter(explode(',', $fileIds), 'is_numeric');
            $files = File::whereIn('id', $ids)->lockForUpdate()->get();
                     $zip = new ZipArchive();
            $zipFileName = 'files_' . now()->format('Y-m-d_His') . '.zip';
            $zipPath = realpath(storage_path('app')) . DIRECTORY_SEPARATOR . $zipFileName;

            if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
                return response()->json(['message' => 'Cannot create zip file.'], 500);
            }

            foreach ($files as $file) {
                if (!$this->isFileLocked($file->id)) {
                    return response()->json(['message' => 'Cannot Download File Because it is Locked.'], 500);
                }
                $filePath = storage_path('app' . $file->path);
                if (!file_exists($filePath)) {
                    continue;
                }

                // Lock file for downloading
                $this->lockFile($file, 1, Auth::user()->id);
                $zip->addFile($filePath, basename($file->name));
                // Log file download attempt
                $this->logFileChanges($request, $file->id, null, 'download');
            }

            if (!$zip->close()) {
                return response()->json(['message' => 'Failed to close ZIP file.'], 500);
            }

            if (!file_exists($zipPath)) {
                return response()->json(['message' => 'ZIP file was not created.'], 500);
            }

            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        });
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

    public function lockFile($file, $status, $locked)
    {
        event(new TransactionEvent('start'));
        try {
            $file->locked_by = $locked;
            $file->status = $status;
            $file->save();


            event(new TransactionEvent('commit'));
        } catch (\Exception $e) {
            event(new TransactionEvent('rollback'));
            throw $e;
        }
        return response()->json(['status' => 'success']);
    }




    public static function report($fileId, $from, $to)
    {
        $file = File::with(
            'fileLogs'
        )->findOrFail($fileId);

        return $file;
    }


    public static function getArchive($request, $fileId)
    {
        $file = File::with('archive')->findOrFail($fileId);

        return $file;
    }
}
