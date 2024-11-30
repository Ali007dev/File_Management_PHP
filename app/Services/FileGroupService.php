<?php

namespace App\Services;
use App\Models\FileGroup;

class FileGroupService extends BaseService
{
    public function __construct(FileGroup $model)
    {
        parent::__construct($model);
    }
}

