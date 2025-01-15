<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Resources\FileResource;
use App\Services\FileService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

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

    public function downloadFile(Request $request,$ids){
        $data =   app(FileService::class)->downloadMultipleFiles( $request,$ids);
        return $data;

      }

      public function openFile($file){
        $data =   app(FileService::class)->logOperation($file,'open');
        return $this->success($data);

      }


      public function report(Request $request, $file) {
        $data = app(FileService::class)->report($file, $request->from_date, $request->to_date);

        $pdfData = [
            'name' => $data->name,
            'status' => $data->status,
            'userName' => $data->user->name,
            'groupName' => $data->groups[0]->name,
            'fileLogs' => $data->fileLogs
        ];

        $pdf = FacadePdf::loadView('report_template', ['data' => $pdfData]);

        return $pdf->download('report.pdf');
    }
}
