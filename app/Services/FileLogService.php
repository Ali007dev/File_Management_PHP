<?php

namespace App\Services;
use App\Models\FileLog;

class FileLogService extends BaseService
{
    public function __construct(FileLog $model)
    {
        parent::__construct($model);
    }



}

