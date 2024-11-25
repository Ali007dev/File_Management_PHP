<?php

namespace App\Services;
use App\Models\UserGroup;

class UserGroupService extends BaseService
{
    public function __construct(UserGroup $model)
    {
        parent::__construct($model);
    }
}

