<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    public function uploadFile($disk, $file)
    {
        $allowedfileExtension = ['jpg', 'png', 'JPEG', 'PNG', 'jpeg','pdf','docx','txt'];
        $extension = $file->getClientOriginalExtension();
        $check = in_array($extension, $allowedfileExtension);
        if (!$check) {
            return "";
        }
        $path = Storage::disk($disk)->put('', $file);
        return "/$disk/$path";
    }

    public function deleteFile($disk, $path)
    {
        try {
            if (str_contains($path, "default")) {
                return true;
            }
            $exploded = explode('/', $path);
            $path = $exploded[sizeof($exploded) - 1];
            Storage::disk($disk)->delete( $path);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
