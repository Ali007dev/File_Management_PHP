<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends BaseCRUDController
{
    public function __construct(FileService $service) {
        parent::__construct(
            $service,
            CreateFileRequest::class,
            UpdateFileRequest::class,
            FileResource::class
        );


    }

    public function upload(CreateFileRequest $request){
      return app(FileService::class)->upload( $request);
    }
}
