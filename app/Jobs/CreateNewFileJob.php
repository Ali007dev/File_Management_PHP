<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\FileGroup;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateNewFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $filePath;
    protected $name;
    protected $fileSize;
    protected $request;

    public function __construct($userId, $filePath, $name, $fileSize,$request)
    {
        $this->userId = $userId;
        $this->filePath = $filePath;
        $this->name = $name;
        $this->fileSize = $fileSize;
        $this->request = $request;

    }

    public function handle()
    {
        $file = File::create([
            'user_id' => $this->userId,
            'path' => $this->filePath,
            'name' => $this->name,
            'size' => $this->fileSize,
            'status' => false,
        ]);
        LogFileChangesJob::dispatch($this->userId, $file->id, 'add', null);


       return FileGroup::create([
            'file_id' =>$file->id,
            'group_id' => $this->request,
        ]);
    }
}
