<?php

namespace App\Repositories;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;

class FileRepository extends BaseRepository
{
    /**
     * Constructor for FileRepository.
     *
     * @param File $model
     */
    public function __construct(File $model)
    {
        parent::__construct($model);
    }



}
