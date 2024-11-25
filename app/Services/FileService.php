<?php

namespace App\Services;
use App\Models\File;

class FileService extends BaseService
{
    public function __construct(File $model)
    {
        parent::__construct($model);
    }
}

