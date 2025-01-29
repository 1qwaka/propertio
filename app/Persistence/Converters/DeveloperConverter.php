<?php

namespace App\Persistence\Converters;

use App\Domain\Developer\DeveloperEntity;
use App\Models\Developer;

class DeveloperConverter
{
    public static function toDomain(Developer $model): DeveloperEntity
    {
        return new DeveloperEntity(
            id: $model->id,
            address: $model->address,
            name: $model->name,
            rating: $model->rating,
            email: $model->email,
        );
    }
}
