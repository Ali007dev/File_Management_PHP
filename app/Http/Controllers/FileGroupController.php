<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateFileGroupRequest;
use App\Http\Requests\UpdateFileGroupRequest;
use App\Http\Resources\FileGroupResource;
use App\Services\FileGroupService;

class FileGroupController extends BaseCRUDController
{
    public function __construct(FileGroupService $service) {
        parent::__construct(
            $service,
            CreateFileGroupRequest::class,
            UpdateFileGroupRequest::class,
            FileGroupResource::class
        );
    }
}
