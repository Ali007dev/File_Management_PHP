<?php

namespace App\Jobs;

use App\Models\Group;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $operation;
    protected $groupId;

    public function __construct($operation, $groupId)
    {
        $this->operation = $operation;
        $this->groupId = $groupId;
    }

    public function handle()
    {
        $group = Group::findOrFail($this->groupId);
        $users = User::whereIn('id', UserGroup::where('group_id', $this->groupId)->pluck('user_id'))->get();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'user_id' => $user->id,
                'content' => "{$user->name} has performed {$this->operation} on a file in Group: {$group->name}.",
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Notification::insert($data);
    }
}
