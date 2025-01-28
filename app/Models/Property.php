<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'renovation',
        'building_id',       // FK to Building
        'floor',
        'area',
        'floor_type_id',     // FK to FloorType
        'address',
        'living_space_type', // Возможно, еще один словарный тип
        'agent_id',          // FK to Agent
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

}
