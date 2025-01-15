<?php

namespace App\Repositories;

use App\Models\FileLog;

interface FileLogRepositoryInterface
{
    public function all();
    public function paginate();
    public function find($id): ?FileLog;
    public function create(array $data): FileLog;
    public function update(FileLog $fileLog, array $data): FileLog;
    public function delete(FileLog $fileLog): bool;

    public function findByDateRange($from, $to);
}
