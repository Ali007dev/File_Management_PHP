<?php

namespace App\Repositories;

use App\Models\FileGroup;

interface FileGroupRepositoryInterface
{
    public function all();
    public function paginate();
    public function find($id): ?FileGroup;
    public function create(array $data): FileGroup;
    public function update(FileGroup $fileGroup, array $data): FileGroup;
    public function delete(FileGroup $fileGroup): bool;
}
