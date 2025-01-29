<?php

namespace App\Persistence\Converters;

use App\Domain\ViewRequest\ViewRequestEntity;
use App\Models\ViewRequest;
use Carbon\Carbon;

class ViewRequestConverter
{
    public static function toDomain(ViewRequest $model): ViewRequestEntity
    {
        return new ViewRequestEntity(
            id: $model->id,
            status: $model->status,
            date: Carbon::parse($model->date),
            propertyId: $model->property_id,
            userId: $model->user_id,
        );
    }
}
