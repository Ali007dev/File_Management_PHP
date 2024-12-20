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
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

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

            if ($fileId) {
                return $this->modifyExistingFile($request, $fileId, $filePath, $file);
            } else {
                $newFile = $this->createNewFile($request, $filePath);
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

            // Update the file path
            $existingFile->path = $filePath;
            $existingFile->save();
        }
        return  $existingFile;
    }

    private function createNewFile(Request $request, $filePath)
    {
       return  File::create([
            'user_id' => $request->user()->id,
            'path' => $filePath,
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

    public function downloadFile( $request,$fileId)
    {
        $file = File::find($fileId);
        if (!$file) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $this->logDownloadOperation($fileId);

        if ($this->isFileLockedByAnotherUser($file, $request)) {
            return response()->json(['error' => 'This file is currently locked for modification by another user.'], 403);
        }

        return response()->download(storage_path('app/' . $file->path), $file->path . '_' . now()->format('Y-m-d') . '.txt');
    }

    private function logDownloadOperation($fileId)
    {
        FileLog::create([
            'user_id' => Auth::user()->id,
            'file_id' => $fileId,
            'operation' => 'download',
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
