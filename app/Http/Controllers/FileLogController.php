<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateFileLogRequest;
use App\Http\Requests\UpdateFileLogRequest;
use App\Http\Resources\FileLogResource;
use App\Services\FileLogService;

class FileLogController extends BaseCRUDController
{
    public function __construct(FileLogService $service) {
        parent::__construct(
            $service,
            CreateFileLogRequest::class,
            UpdateFileLogRequest::class,
            FileLogResource::class
        );
    }
}
