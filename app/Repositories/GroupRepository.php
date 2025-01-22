<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GroupRepository extends BaseRepository
{
    /**
     * Constructor for FileRepository.
     *
     * @param Group $model
     */
    public function __construct(Group $model)
    {
        parent::__construct($model);
    }

    public function allForCurrentUser()
    {
        $userId = Auth::id();

        return $this->model
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with($this->model->targetWith ?? [])
            ->get();
    }
}
