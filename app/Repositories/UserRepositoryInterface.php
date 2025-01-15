<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function all();
    public function paginate();
    public function find($id): ?User;
    public function create(array $data): User;
    public function update(User $user, array $data): User;
    public function delete(User $user): bool;

    public function findWithRelations($id, array $relations = []);
    public function getUserFiles($userId);
    public function getUserGroups($userId);
    public function getUserNotifications($userId);
    public function report($userId, $from, $to);
    public function getAuthenticatedUser($id);
}
