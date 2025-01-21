<?php

namespace App\Services;
use App\Models\FileLog;
use App\Repositories\FileLogRepository;

class FileLogService extends BaseService
{
    public function __construct(FileLogRepository $repository)
    {
        parent::__construct($repository);
    }

}

