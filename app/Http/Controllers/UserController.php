<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
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


    public function report(Request $request,$user){
        $data =   app(UserService::class)->report($user,$request->from_date,$request->to_date );
        return $this->success($data);

      }

      public function me(){
        $data =   app(UserService::class)->me( );
        return $this->success(UserResource::make($data));

      }
}
