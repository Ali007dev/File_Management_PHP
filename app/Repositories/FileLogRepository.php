<?php

namespace App\Repositories;

use App\Models\FileLog;

class FileLogRepository extends BaseRepository
{
    public function __construct(FileLog $model)
    {
        parent::__construct($model);
    }

}
