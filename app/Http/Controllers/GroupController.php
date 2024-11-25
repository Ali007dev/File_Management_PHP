<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseCRUDController;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Services\GroupService;

class GroupController extends BaseCRUDController
{
    public function __construct(GroupService $service) {
        parent::__construct(
            $service,
            CreateGroupRequest::class,
            UpdateGroupRequest::class,
            GroupResource::class
        );
    }
}
