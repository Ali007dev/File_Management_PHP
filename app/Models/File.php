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
    use SoftDeletes;
    use Filterable;

    protected $fillable = [
        'status',
        'path',
        'user_id',
    ];

    protected $filterable = [
        'status'=> LikeFilter::class,
        'groups'=>Group::class,
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fileLogs()
    {
        return $this->hasMany(FileLog::class);
    }
}
