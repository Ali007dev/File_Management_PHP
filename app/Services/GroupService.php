<?php

namespace App\Services;
use App\Models\Group;
use App\Repositories\GroupRepository;

class GroupService extends BaseService
{
    public function __construct(GroupRepository $repository)
    {
        parent::__construct($repository);
    }

    public function showById($group)
    {
        $data =Group::with('files')->findOrFail($group);
        return $data;
    }
}

