<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',        // FK to BuildingType
        'hot_water',
        'gas',
        'elevators',
        'floors',
        'build_year',
//        'district_id',    // FK to District
        'developer_id',   // FK to Developer
        'address',
    ];

    public static function findTypeByName(string $name) {
        return DB::table('building_type')->where('name', $name)->first();
    }
}
