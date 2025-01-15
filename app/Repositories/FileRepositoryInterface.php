<?php

namespace App\Repositories;

use App\Models\File;

interface FileRepositoryInterface
{
    // Basic CRUD operations
    public function all();
    public function paginate();
    public function find($id): ?File;
    public function create(array $data): File;
    public function update(File $file, array $data): File;
    public function delete(File $file): bool;

    public function findWithRelations($id, array $relations = []);
    public function findMany(array $ids);

    public function logFileOperation(array $data);
    public function createFileGroup(array $data);
}
