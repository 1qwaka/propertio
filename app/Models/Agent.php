<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'name',
        'address',
        'email',
        'user_id',
    ];

    protected $hidden = [
        'type_id',
    ];

    protected $appends = [
        'type_name'
    ];

    public function getTypeNameAttribute()
    {
        return DB::table('agent_type')->where('id', '=', $this->type_id)->first()->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findTypeById(string $name) {
        return DB::table('agent_type')->where('name', $name)->get()->first();
    }
}
