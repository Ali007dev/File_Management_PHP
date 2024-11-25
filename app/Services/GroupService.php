<?php

namespace App\Services;
use App\Models\Group;

class GroupService extends BaseService
{
    public function __construct(Group $model)
    {
        parent::__construct($model);
    }
}

