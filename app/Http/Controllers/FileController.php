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
    public function uploadOrModify(CreateFileRequest $request){
      $data =  app(FileService::class)->uploadOrModify( $request,$request->fileId);
      return $this->success($data);
    }

    public function downloadFile(Request $request,$file){
        $data =   app(FileService::class)->downloadFile( $request,$file);
        return $this->success($data);

      }

      public function openFile($file){
        $data =   app(FileService::class)->logOperation($file,'open');
        return $this->success($data);

      }


    public function report(Request $request,$file){
        $data =   app(FileService::class)->report($file,$request->from_date,$request->to_date );
        return $this->success($data);

      }
}
