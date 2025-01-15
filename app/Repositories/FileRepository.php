<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\FileGroup;
use App\Models\FileLog;

class FileRepository implements FileRepositoryInterface
{
    public function all()
    {
        return File::all();
    }

    public function paginate()
    {
        return File::paginate();
    }

    public function find($id): ?File
    {
        return File::find($id);
    }

    public function create(array $data): File
    {
        return File::create($data);
    }

    public function update(File $file, array $data): File
    {
        $file->update($data);
        return $file;
    }

    public function delete(File $file): bool
    {
        return $file->delete();
    }

    public function findWithRelations($id, array $relations = [])
    {
        return File::with($relations)->find($id);
    }

    public function findMany(array $ids)
    {
        return File::findMany($ids);
    }

    public function logFileOperation(array $data)
    {
        return FileLog::create($data);
    }

    public function createFileGroup(array $data)
    {
        return FileGroup::create($data);
    }
}
