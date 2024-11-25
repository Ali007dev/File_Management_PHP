<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;

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
}
