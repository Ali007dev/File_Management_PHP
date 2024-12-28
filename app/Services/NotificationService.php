<?php

namespace App\Services;
use App\Models\FileGroup;
use App\Models\Group;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserGroup;

class NotificationService
{

    public function sendNotification($operation, $groupId)
    {
        $group = Group::findOrFail($groupId);
        $users = User::whereIn('id', UserGroup::where('group_id', $groupId)->pluck('user_id'))->get();  // استرجاع المستخدمين في المجموعة

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'user_id' => $user->id,
                'content' => "{$user->name} has performed $operation on a file in Group: {$group->name}.",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Notification::insert($data);
    }

}

