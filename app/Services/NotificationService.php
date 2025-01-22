<?php

namespace App\Services;

use App\Jobs\SendNotificationJob;
use App\Models\FileGroup;
use App\Models\Group;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserGroup;

class NotificationService
{

    public function sendNotification($operation, $groupId)
    {
        SendNotificationJob::dispatch($operation, $groupId);

    }

}

