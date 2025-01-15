<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function paginate()
    {
        return User::paginate();
    }

    public function find($id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function findWithRelations($id, array $relations = [])
    {
        return User::with($relations)->find($id);
    }

    public function getUserFiles($userId)
    {
        return User::find($userId)->files;
    }

    public function getUserGroups($userId)
    {
        return User::find($userId)->groups;
    }

    public function getUserNotifications($userId)
    {
        return \App\Models\Notification::where('user_id', $userId)->get();
    }

    public function report($userId, $from, $to)
    {
        return User::with(['files.fileLogs' => function ($query) use ($from, $to) {
            $query->dateBetween($from, $to);
        }])->findOrFail($userId);
    }

    public function getAuthenticatedUser($id)
    {
        return User::with(['files.fileLogsOpen' => function ($query) {
            $query->latest()->take(10);
        }])->findOrFail($id);
    }
}
