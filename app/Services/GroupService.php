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
        $data =Group::with('users',
       'files.fileLogs',
        'files.lastModify',
        'files.lastView')->findOrFail($group);
        return $data;
    }
}

