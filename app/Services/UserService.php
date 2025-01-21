<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public static function report($userId, $from, $to)
    {
        $user = User::with(['fileLogs' => function ($query) use ($from, $to) {
            $query->dateBetween($from, $to);
        }])->findOrFail($userId);


        return $user;
    }

    public static function me()
    {
        $user = User::with(['files.fileLogsOpen' => function($query) {
            $query->latest()->take(10);
        }])->findOrFail(Auth::id());

        return $user;
    }


    public function notification(){
        $data =  Notification::where('user_id',Auth::user()->id)->get();
        return $data;

      }

}

