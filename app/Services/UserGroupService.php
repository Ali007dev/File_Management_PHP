<?php

namespace App\Services;
use App\Models\UserGroup;
use App\Repositories\UserGroupRepository;

class UserGroupService extends BaseService
{

    public function __construct(UserGroupRepository $repository)
    {
        parent::__construct($repository);
    }
}

