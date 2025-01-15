<?php

namespace App\Repositories;

use App\Models\Notification;

interface NotificationRepositoryInterface
{
    public function all();
    public function find($id): ?Notification;
    public function create(array $data): Notification;
    public function bulkInsert(array $data): void;
    public function getByUserId($userId);
}
