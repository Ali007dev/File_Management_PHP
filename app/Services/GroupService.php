<?php

namespace App\Services;
use App\Models\Group;

class GroupService extends BaseService
{
    public function __construct(Group $model)
    {
        parent::__construct($model);
    }

    public function showById($group)
    {
        $data =Group::with('files')->findOrFail($group);
        return $data;
    }
}

