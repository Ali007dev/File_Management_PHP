<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use App\Traits\Filterable;

class UserGroup extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'isAdmin','group_id','user_id'
    ];

    protected $filterable = [
        'users'=>User::class,
        'groups'=>Group::class
    ];


}
