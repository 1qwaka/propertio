<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',      // FK to Agent
        'description',
        'price',
        'property_id',   // FK to Property
        'type',          // enum: opened || accepted || rejected
        'hidden',        // Boolean для скрытия объявления
    ];


}
