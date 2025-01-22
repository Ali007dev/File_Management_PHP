<?php
namespace App\Jobs;

use App\Models\FileLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogFileChangesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $fileId;
    protected $operation;
    protected $diff;

    public function __construct($userId, $fileId, $operation, $diff)
    {
        $this->userId = $userId;
        $this->fileId = $fileId;
        $this->operation = $operation;
        $this->diff = $diff;
    }

    public function handle()
    {

        FileLog::create([
            'user_id' => $this->userId,
            'file_id' => $this->fileId,
            'operation' => $this->operation,
            'file' => json_encode($this->diff, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            'date' => now(),
        ]);
    }
}
