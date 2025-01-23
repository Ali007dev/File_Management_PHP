<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\FileLogService;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request ;

class UserController extends BaseCRUDController
{
    public function __construct(UserService $service) {
        parent::__construct(
            $service,
            CreateUserRequest::class,
            UpdateUserRequest::class,
            UserResource::class
        );
    }


    public function report(Request $request, $user) {
        $data = app(UserService::class)->report($user, $request->from_date, $request->to_date);

        $fileLogs = $data->fileLogs->toArray();

        if (empty($fileLogs)) {
            abort(400, "No logs found for this user.");
        }

        $pdfData = [
            'userName' => $data->name,
            'email' => $data->email,
            'number' => $data->number,
            'fileLogs' => $fileLogs
        ];

        $pdf = PDF::loadView('user_report_template', ['data' => $pdfData]);
        return $pdf->download('user_report.pdf');
    }

      public function me(){
        $data =   app(UserService::class)->me( );
        return $this->success(UserResource::make($data));

      }

      public function notification(){
        $data =   app(UserService::class)->notification( );
        return $this->success($data);

      }
}
