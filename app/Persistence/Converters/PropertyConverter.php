<?php

namespace App\Persistence\Converters;

use App\Domain\Property\LivingSpaceType;
use App\Domain\Property\PropertyEntity;
use App\Models\Property;

class PropertyConverter
{
    public static function toDomain(Property $model): PropertyEntity
    {
        return new PropertyEntity(
            id: $model->id,
            renovation: $model->renovation,
            buildingId: $model->building_id,
            floor: $model->floor,
            area: $model->area,
            floorTypeId: $model->floor_type_id,
            address: $model->address,
            livingSpaceType: LivingSpaceType::from($model->living_space_type),
            agentId: $model->agent_id,
        );
    }

}
