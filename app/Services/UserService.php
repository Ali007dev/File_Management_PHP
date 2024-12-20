<?php

namespace App\Services;
use App\Models\User;

class UserService extends BaseService
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public static function report($userId, $from, $to)
    {
        $user = User::with(['fileLogs' => function ($query) use ($from, $to) {
            $query->dateBetween($from, $to);
        }])->findOrFail($userId);

        return $user;
    }

}

