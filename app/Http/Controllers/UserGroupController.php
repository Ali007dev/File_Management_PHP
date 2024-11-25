<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateUserGroupRequest;
use App\Http\Requests\UpdateUserGroupRequest;
use App\Http\Resources\UserGroupResource;
use App\Services\UserGroupService;

class UserGroupController extends BaseCRUDController
{
    public function __construct(UserGroupService $service) {
        parent::__construct(
            $service,
            CreateUserGroupRequest::class,
            UpdateUserGroupRequest::class,
            UserGroupResource::class
        );
    }
}
