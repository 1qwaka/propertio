<?php

namespace Tests\Util;

use App\Domain\Building\BuildingEntity;
use App\Domain\Building\BuildingPageDto;
use App\Domain\Building\CreateBuildingDto;
use App\Domain\Building\UpdateBuildingDto;

class BuildingMother
{
    public const DEFAULT_ID = 1;
    public const DEFAULT_TYPE_ID = 1;
    public const DEFAULT_HOT_WATER = true;
    public const DEFAULT_GAS = true;
    public const DEFAULT_ELEVATORS = 2;
    public const DEFAULT_FLOORS = 5;
    public const DEFAULT_BUILD_YEAR = 2000;
    public const DEFAULT_DEVELOPER_ID = 101;
    public const DEFAULT_ADDRESS = '123 Main St';

    public static function defaultCreateBuildingDto(): CreateBuildingDto
    {
        return new CreateBuildingDto(
            self::DEFAULT_TYPE_ID,
            self::DEFAULT_FLOORS,
            self::DEFAULT_BUILD_YEAR,
            self::DEFAULT_DEVELOPER_ID,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_HOT_WATER,
            self::DEFAULT_GAS,
            self::DEFAULT_ELEVATORS
        );
    }

    public static function defaultUpdateBuildingDto(?int $id = null): UpdateBuildingDto
    {
        return new UpdateBuildingDto(
            $id ?? self::DEFAULT_ID,
            self::DEFAULT_TYPE_ID,
            self::DEFAULT_HOT_WATER,
            self::DEFAULT_GAS,
            self::DEFAULT_ELEVATORS,
            self::DEFAULT_FLOORS,
            self::DEFAULT_BUILD_YEAR,
            self::DEFAULT_DEVELOPER_ID,
            self::DEFAULT_ADDRESS
        );
    }

    public static function defaultBuildingPageDto(): BuildingPageDto
    {
        return new BuildingPageDto(
            100,
            1,
            10,
            []
        );
    }

    public static function defaultBuildingEntity(): BuildingEntity
    {
        return new BuildingEntity(
            self::DEFAULT_ID,
            self::DEFAULT_TYPE_ID,
            self::DEFAULT_HOT_WATER,
            self::DEFAULT_GAS,
            self::DEFAULT_ELEVATORS,
            self::DEFAULT_FLOORS,
            self::DEFAULT_BUILD_YEAR,
            self::DEFAULT_DEVELOPER_ID,
            self::DEFAULT_ADDRESS
        );
    }

    public static function buildingEntityWithParams(
        ?int $id = null,
        int $typeId = self::DEFAULT_TYPE_ID,
        ?bool $hotWater = self::DEFAULT_HOT_WATER,
        ?bool $gas = self::DEFAULT_GAS,
        ?int $elevators = self::DEFAULT_ELEVATORS,
        int $floors = self::DEFAULT_FLOORS,
        int $buildYear = self::DEFAULT_BUILD_YEAR,
        int $developerId = self::DEFAULT_DEVELOPER_ID,
        string $address = self::DEFAULT_ADDRESS
    ): BuildingEntity {
        return new BuildingEntity(
            $id ?? self::DEFAULT_ID,
            $typeId,
            $hotWater,
            $gas,
            $elevators,
            $floors,
            $buildYear,
            $developerId,
            $address
        );
    }
}
