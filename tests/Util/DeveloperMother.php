<?php

namespace Tests\Util;

use App\Domain\Developer\CreateDeveloperDto;
use App\Domain\Developer\DeveloperEntity;
use App\Domain\Developer\DeveloperPageDto;
use App\Domain\Developer\GetDevelopersDto;
use App\Domain\Developer\UpdateDeveloperDto;

class DeveloperMother
{
    public const DEFAULT_ID = 1;
    public const DEFAULT_NAME = 'Default Developer';
    public const DEFAULT_ADDRESS = '123 Developer Ave';
    public const DEFAULT_RATING = 4.5;
    public const DEFAULT_EMAIL = 'developer@example.com';

    public static function defaultCreateDeveloperDto(): CreateDeveloperDto
    {
        return new CreateDeveloperDto(
            self::DEFAULT_ADDRESS,
            self::DEFAULT_NAME,
            self::DEFAULT_RATING,
            self::DEFAULT_EMAIL
        );
    }

    public static function defaultUpdateDeveloperDto(?int $id = null): UpdateDeveloperDto
    {
        return new UpdateDeveloperDto(
            $id ?? self::DEFAULT_ID,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_NAME,
            self::DEFAULT_RATING,
            self::DEFAULT_EMAIL
        );
    }

    public static function defaultGetDevelopersDto(): GetDevelopersDto
    {
        return new GetDevelopersDto(
            1,
            10,
            null,
            null
        );
    }

    public static function defaultDeveloperPageDto(): DeveloperPageDto
    {
        return new DeveloperPageDto(
            100,
            1,
            10,
            []
        );
    }

    public static function defaultDeveloperEntity(): DeveloperEntity
    {
        return new DeveloperEntity(
            self::DEFAULT_ID,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_NAME,
            self::DEFAULT_RATING,
            self::DEFAULT_EMAIL
        );
    }

    public static function developerEntityWithParams(
        ?int $id = null,
        string $address = self::DEFAULT_ADDRESS,
        string $name = self::DEFAULT_NAME,
        ?float $rating = self::DEFAULT_RATING,
        string $email = self::DEFAULT_EMAIL
    ): DeveloperEntity {
        return new DeveloperEntity(
            $id ?? self::DEFAULT_ID,
            $address,
            $name,
            $rating,
            $email
        );
    }
}

