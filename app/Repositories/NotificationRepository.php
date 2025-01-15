<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function all()
    {
        return Notification::all();
    }

    public function find($id): ?Notification
    {
        return Notification::find($id);
    }

    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    public function bulkInsert(array $data): void
    {
        Notification::insert($data);
    }

    public function getByUserId($userId)
    {
        return Notification::where('user_id', $userId)->get();
    }
}

