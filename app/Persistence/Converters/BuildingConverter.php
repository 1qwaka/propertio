<?php

namespace App\Persistence\Converters;

use App\Domain\Building\BuildingEntity;
use App\Models\Building;

class BuildingConverter
{
    public static function toDomain(Building $building): BuildingEntity
    {
        return new BuildingEntity(
            id: $building->id,
            typeId: $building->type_id,
            hotWater: $building->hot_water,
            gas: $building->gas,
            elevators: $building->elevators,
            floors: $building->floors,
            buildYear: $building->build_year,
            developerId: $building->developer_id,
            address: $building->address,
        );
    }
}
