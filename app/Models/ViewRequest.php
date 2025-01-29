<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',       // enum: 'open', 'accepted', 'rejected'
        'date',
        'property_id',  // FK to Property
        'user_id',      // FK to User
    ];

}
