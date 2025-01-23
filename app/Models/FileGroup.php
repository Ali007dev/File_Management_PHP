<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use App\Traits\Filterable;

class FileGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Filterable;

    protected $fillable = [
        'file_id',
        'group_id',
    ];

    protected $filterable = [

    ];


    protected static function booted()
    {
        static::deleting(function ($fileGroup) {
            $fileGroup->file->delete();
        });
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
