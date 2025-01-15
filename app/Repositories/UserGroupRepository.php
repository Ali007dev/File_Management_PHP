<?php

namespace App\Repositories;

use App\Models\UserGroup;

class UserGroupRepository implements UserGroupRepositoryInterface
{
    public function all()
    {
        return UserGroup::all();
    }

    public function paginate()
    {
        return UserGroup::paginate();
    }

    public function find($id): ?UserGroup
    {
        return UserGroup::find($id);
    }

    public function create(array $data): UserGroup
    {
        return UserGroup::create($data);
    }

    public function update(UserGroup $userGroup, array $data): UserGroup
    {
        $userGroup->update($data);
        return $userGroup;
    }

    public function delete(UserGroup $userGroup): bool
    {
        return $userGroup->delete();
    }
}
