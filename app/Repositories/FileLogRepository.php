<?php

namespace App\Repositories;

use App\Models\FileLog;

class FileLogRepository implements FileLogRepositoryInterface
{
    public function all()
    {
        return FileLog::all();
    }

    public function paginate()
    {
        return FileLog::paginate();
    }

    public function find($id): ?FileLog
    {
        return FileLog::find($id);
    }

    public function create(array $data): FileLog
    {
        return FileLog::create($data);
    }

    public function update(FileLog $fileLog, array $data): FileLog
    {
        $fileLog->update($data);
        return $fileLog;
    }

    public function delete(FileLog $fileLog): bool
    {
        return $fileLog->delete();
    }

    public function findByDateRange($from, $to)
    {
        return FileLog::dateBetween($from, $to)->get();
    }
}
