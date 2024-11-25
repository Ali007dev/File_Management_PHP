<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Str;

Trait StoresImage {
    public function image() : Attribute{
        return Attribute::make(
            get: fn($value)=>"storage/"
            .($value?$value:$this->getImageStoragePath().$this->getDefaultImageName())
        );
    }
    public function deleteImage()
    {
        $fieldName = $this->getImageFieldName();
        $oldImageName = $this->getRawOriginal($fieldName);
        //get all the files with the same name through (glob)..then delete them
        if ($oldImageName != null) {
            array_map(
                'unlink',
                glob(storage_path("app/public/$oldImageName"))
            );
        }
    }

    public function updateImage($image)
    {
        $fieldName = $this->getImageFieldName();
        //remove the old picture
        $this->deleteImage();
        //add the new picture
        $imageName = Str::random(7) . "_" . now()->format('Y-m-d_H-i-s') . ".{$image->extension()}";
        $this->$fieldName = ($this->getImageStoragePath()).$imageName;
        $this->save();
        $image
            ->storeAs('public/'.($this->getImageStoragePath()), $imageName);
    }

    public function getImageFieldName() : String {
        return "image";
    }
    public function getImageStoragePath() : String {
        return "images/temp/";
    }
    public function getDefaultImageName() : String {
        return "default.png";
    }

    public static final function getImageRules() :array {
        // return ['file','mimes:jpeg,png,jpg','max:2048'];
        return ['file','mimes:jpeg,png,jpg'];
    }

}

