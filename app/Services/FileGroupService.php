<?php

namespace App\Services;
use App\Models\FileGroup;
use App\Repositories\FileGroupRepository;

class FileGroupService extends BaseService
{
    public function __construct(FileGroupRepository $repository)
    {
        parent::__construct($repository);
    }
}

