<?php

namespace App\Services;

use App\Models\File;
use App\Models\FileGroup;
use Illuminate\Http\Request;
use App\Traits\FileTrait;
use Illuminate\Support\Facades\Auth;

class FileService extends BaseService
{
    use FileTrait;
    public function __construct(File $model)
    {
        parent::__construct($model);
    }

    public function upload($request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $disk = 'public';

            $filePath = $this->uploadFile($disk, $file);

            $stored_file = File::create([
                'path' => $filePath,
                'user_id' => Auth::user()->id,
            ]);
            $file_group = FileGroup::create([
                'file_id' => $stored_file->id,
                'group_id'=>$request->group_id
            ]);
            return $stored_file ;
        }
    }
}
