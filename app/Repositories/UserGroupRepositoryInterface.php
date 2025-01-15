<?php

namespace App\Repositories;

use App\Models\UserGroup;

interface UserGroupRepositoryInterface
{
    public function all();
    public function paginate();
    public function find($id): ?UserGroup;
    public function create(array $data): UserGroup;
    public function update(UserGroup $userGroup, array $data): UserGroup;
    public function delete(UserGroup $userGroup): bool;
}
