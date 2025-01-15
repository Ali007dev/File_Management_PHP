<?php

namespace App\Repositories;

use App\Models\Group;

class GroupRepository implements GroupRepositoryInterface
{
    public function all()
    {
        return Group::all();
    }

    public function paginate()
    {
        return Group::paginate();
    }

    public function find($id): ?Group
    {
        return Group::find($id);
    }

    public function create(array $data): Group
    {
        return Group::create($data);
    }

    public function update(Group $group, array $data): Group
    {
        $group->update($data);
        return $group;
    }

    public function delete(Group $group): bool
    {
        return $group->delete();
    }

    public function findWithRelations($id, array $relations = [])
    {
        return Group::with($relations)->find($id);
    }
}
