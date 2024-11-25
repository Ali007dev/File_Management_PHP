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

    protected $filterable = [
        'name'=>LikeFilter::class,
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'users_groups');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
