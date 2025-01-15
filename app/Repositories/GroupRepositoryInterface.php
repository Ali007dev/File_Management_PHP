<?php

namespace App\Repositories;

use App\Models\Group;

interface GroupRepositoryInterface
{
    public function all();
    public function paginate();
    public function find($id): ?Group;
    public function create(array $data): Group;
    public function update(Group $group, array $data): Group;
    public function delete(Group $group): bool;

    public function findWithRelations($id, array $relations = []);
}
