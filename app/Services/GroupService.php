<?php

namespace App\Services;
use App\Models\Group;
use App\Models\UserGroup;
use App\Repositories\GroupRepository;
use Illuminate\Support\Facades\Auth;

class GroupService extends BaseService
{

    protected $repository;

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function create($data)
    {
        $group = parent::create($data);

        $this->addAdminToGroup($group->id, Auth::id());

        return $group;
    }

    /**
     * أضف المستخدم كأدمن إلى المجموعة
     *
     * @param int $groupId ID المجموعة
     * @param int $userId ID المستخدم
     */
    protected function addAdminToGroup($groupId, $userId)
    {
        UserGroup::create([
            'group_id' => $groupId,
            'user_id' => $userId,
            'isAdmin' => true
        ]);
    }

    public function showById($group)
    {
        $data =Group::with('users',
       'files.fileLogs',
       'files.groups',
       'files.archive',
        'files.lastModify',
        'files.lastView')->findOrFail($group);
        return $data;
    }

    public function allForCurrentUser()
    {
        return $this->repository->allForCurrentUser();
    }
}

