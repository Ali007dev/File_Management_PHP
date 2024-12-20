<?php
namespace App\Models;

use App\Classes\FilterType\LikeFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use App\Traits\Filterable;

class File extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'status',
        'path',
        'user_id',
    ];

    protected $with = [
        'user',
        'groups',
        'fileLogs',
        'lastModify'
    ];

    protected $filterable = [
        'status'=> LikeFilter::class,
        'groups'=>Group::class,
    ];


    public function groups()
    {
        return $this->belongsToMany(Group::class, 'file_groups');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileLogs()
    {
        return $this->hasMany(FileLog::class);
    }
    public function lastModify()
    {
        return $this->hasOne(FileLog::class)->where('operation','modified')->latest();
    }
}
