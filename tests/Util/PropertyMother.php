<?php

namespace Tests\Util;

use App\Domain\Property\CreatePropertyDto;
use App\Domain\Property\GetPropertiesDto;
use App\Domain\Property\LivingSpaceType;
use App\Domain\Property\PropertiesPageDto;
use App\Domain\Property\PropertyEntity;
use App\Domain\Property\UpdatePropertyDto;

class PropertyMother
{
    public const DEFAULT_ID = 1;
    public const DEFAULT_BUILDING_ID = 1;
    public const DEFAULT_FLOOR = 3;
    public const DEFAULT_FLOOR_TYPE_ID = 1;
    public const DEFAULT_ADDRESS = '123 Main St';
    public const DEFAULT_LIVING_SPACE_TYPE = LivingSpaceType::PRIMARY;
    public const DEFAULT_RENOVATION = 'Newly renovated';
    public const DEFAULT_AREA = 50;
    public const DEFAULT_AGENT_ID = 1;

    public static function defaultCreatePropertyDto(): CreatePropertyDto
    {
        return new CreatePropertyDto(
            self::DEFAULT_BUILDING_ID,
            self::DEFAULT_FLOOR,
            self::DEFAULT_FLOOR_TYPE_ID,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_LIVING_SPACE_TYPE,
            self::DEFAULT_RENOVATION,
            self::DEFAULT_AREA,
            self::DEFAULT_AGENT_ID
        );
    }

    public static function defaultUpdatePropertyDto(?int $id = null): UpdatePropertyDto
    {
        return new UpdatePropertyDto(
            $id ?? self::DEFAULT_ID,
            self::DEFAULT_RENOVATION,
            self::DEFAULT_FLOOR,
            self::DEFAULT_AREA,
            self::DEFAULT_FLOOR_TYPE_ID,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_LIVING_SPACE_TYPE
        );
    }

    public static function defaultGetPropertiesDto(): GetPropertiesDto
    {
        return new GetPropertiesDto(
            1,
            10,
            null,
            null
        );
    }

    public static function defaultPropertiesPageDto(): PropertiesPageDto
    {
        return new PropertiesPageDto(
            100,
            1,
            10,
            []
        );
    }

    public static function defaultPropertyEntity(): PropertyEntity
    {
        return new PropertyEntity(
            self::DEFAULT_ID,
            self::DEFAULT_RENOVATION,
            self::DEFAULT_BUILDING_ID,
            self::DEFAULT_FLOOR,
            (float) self::DEFAULT_AREA,
            self::DEFAULT_FLOOR_TYPE_ID,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_LIVING_SPACE_TYPE,
            self::DEFAULT_AGENT_ID
        );
    }

    public static function propertyEntityWithParams(
        ?int $id = null,
        int $buildingId = self::DEFAULT_BUILDING_ID,
        int $floor = self::DEFAULT_FLOOR,
        int $floorTypeId = self::DEFAULT_FLOOR_TYPE_ID,
        string $address = self::DEFAULT_ADDRESS,
        LivingSpaceType $livingSpaceType = self::DEFAULT_LIVING_SPACE_TYPE,
        ?string $renovation = self::DEFAULT_RENOVATION,
        ?int $area = self::DEFAULT_AREA,
        ?int $agentId = self::DEFAULT_AGENT_ID
    ): PropertyEntity {
        return new PropertyEntity(
            $id ?? self::DEFAULT_ID,
            $renovation,
            $buildingId,
            $floor,
            $area ? (float) $area : null,
            $floorTypeId,
            $address,
            $livingSpaceType,
            $agentId ?? self::DEFAULT_AGENT_ID
        );
    }
}
