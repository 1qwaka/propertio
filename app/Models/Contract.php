<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',       // FK to Property
        'status',            // enum: 'open', 'accepted', 'rejected'
        'date',
        'price',
        'buyer_id',          // FK to User
        'agent_id',          // FK to Agent
        'until',
        'buyer_agreement',
    ];
}
