<?php

namespace App\Repositories;

use App\Models\UserGroup;

class UserGroupRepository extends BaseRepository
{

    /**
     * Constructor for FileRepository.
     *
     * @param UserGroup $model
     */
    public function __construct(UserGroup $model)
    {
        parent::__construct($model);
    }
}
