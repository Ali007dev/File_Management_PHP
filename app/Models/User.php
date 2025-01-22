<?php



namespace App\Models;

use App\Traits\Filterable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use Filterable;


    protected $fillable =['name','password','email','number'];
    protected $hidden =['password'];
    protected $filterable = [
        'name',
    ];

    protected $appends = [
        'size',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getSizeAttribute()
{
    return $this->files()->sum('size');
}


public function groups()
{
    return $this->belongsToMany(Group::class, 'user_groups', 'user_id', 'group_id');
}

    public function files()
    {
        return $this->hasMany(File::class);
    }



    public function fileLogs()
    {
        return $this->hasMany(FileLog::class);
    }



    public function lastFiles()
    {
        return $this->hasMany(File::class);
    }


}

