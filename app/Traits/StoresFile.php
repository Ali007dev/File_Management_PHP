<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Str;

Trait StoresFile {
    public function file() : Attribute{
        return Attribute::make(
            get: fn($value)=>"storage/".$value
        );
    }
    public function deleteFile()
    {
        $fieldName = $this->getFileFieldName();
        $oldFileName = $this->getRawOriginal($fieldName);
        //get all the files with the same name through (glob)..then delete them
        if ($oldFileName != null) {
            array_map(
                'unlink',
                glob(storage_path("app/public/$oldFileName"))
            );
        }
    }

    public function updateFile($file)
    {
        $fieldName = $this->getFileFieldName();
        //remove the old picture
        $this->deleteFile();
        //add the new picture
        $fileName = Str::random(7) . "_" . now()->format('Y-m-d_H-i-s') . ".{$file->extension()}";
        $this->$fieldName = ($this->getFileStoragePath()).$fileName;
        $this->extension = $file->extension();
        $this->save();
        $file
            ->storeAs('public/'.($this->getFileStoragePath()), $fileName);
    }

    public function getFileFieldName() : String {
        return "file";
    }
    public function getFileStoragePath() : String {
        return "files/temp/";
    }
    public static function imageMimes(){
        return "jpeg,png,bmp,tiff,webp,";
    }
    public static function documentMimes(){
        return "pdf,ppt,pptx,";
    }
    public static function videoMimes(){
        return "mp4,mov,avi,mkv,";
    }
    public static function audioMimes(){
        return "mp4,mov,avi,mkv,";
    }


}

