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
        'name',
        'size'
    ];

    protected $with = [
        'user',
        'groups',
        'fileLogs.user',
        'lastModify',
        'lastView'
    ];

    protected $filterable = [
        'status' => LikeFilter::class,
        'groups' => Group::class,
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

    public function fileLogsOpen()
    {
        return $this->hasMany(FileLog::class)->where('operation','open');
    }

    public function lastOpen()
    {
        return $this->hasMany(FileLog::class)->where('operation', 'open')
        ->latest()
        ->take(10);
    }

    public function lastModify()
    {
        return $this->hasOne(FileLog::class)->where('operation', 'modified')->latest();
    }

    public function lastView()
    {
        return $this->hasOne(FileLog::class)->where('operation', 'open')->latest();
    }
}
