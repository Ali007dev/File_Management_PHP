<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use App\Traits\Filterable;

class FileLog extends Model
{
    use HasFactory;
    use Filterable;

    protected $fillable = [
        'user_id',
        'file_id',
        'file',
        'operation',
        'date'

    ];

    protected $filterable = [
        '',
    ];

    protected $with = [
        'MFile',
    ];




    public function scopeDateBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function MFile()
    {
        return $this->belongsTo(File::class,'file_id');
    }
}
