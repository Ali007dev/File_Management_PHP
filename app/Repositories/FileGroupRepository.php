<?php

namespace App\Repositories;

use App\Models\FileGroup;
use FontLib\Table\Type\cmap;

class FileGroupRepository extends BaseRepository
{
      /**
     * Constructor for FileRepository.
     *
     * @param FileGroup $model
     */
    public function __construct(FileGroup $model)
    {
        parent::__construct($model);
    }
}
