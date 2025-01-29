<?php

namespace App\Persistence\Converters;

use App\Domain\Advertisement\AdvertisementEntity;
use App\Domain\Advertisement\AdvertisementStatus;
use App\Models\Advertisement;

class AdvertisementConverter
{
    public static function toDomain(Advertisement $model): AdvertisementEntity
    {
        return new AdvertisementEntity(
            id: $model->id,
            agentId: $model->agent_id,
            description: $model->description,
            price: $model->price,
            propertyId: $model->property_id,
            type: AdvertisementStatus::from($model->type),
            hidden: $model->hidden,
        );
    }
}
