<?php
namespace App\Models;

use App\Classes\FilterType\LikeFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use App\Traits\Filterable;

class Group extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'name',
    ];


    protected $with = [
    ];

    public $relations = [
        'users',
       'files.fileLogs',
        'files.lastModify',
        'files.lastView'
    ];

    protected $filterable = [
        'name'=>LikeFilter::class,
        'users'=>User::class,
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }



    public function files()
    {
        return $this->belongsToMany(File::class, 'file_groups');
    }


}
