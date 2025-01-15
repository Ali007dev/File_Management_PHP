<?php

namespace App\Repositories;

use App\Models\FileGroup;

class FileGroupRepository implements FileGroupRepositoryInterface
{
    public function all()
    {
        return FileGroup::all();
    }

    public function paginate()
    {
        return FileGroup::paginate();
    }

    public function find($id): ?FileGroup
    {
        return FileGroup::find($id);
    }

    public function create(array $data): FileGroup
    {
        return FileGroup::create($data);
    }

    public function update(FileGroup $fileGroup, array $data): FileGroup
    {
        $fileGroup->update($data);
        return $fileGroup;
    }

    public function delete(FileGroup $fileGroup): bool
    {
        return $fileGroup->delete();
    }
}
