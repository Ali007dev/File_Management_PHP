<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

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



}
