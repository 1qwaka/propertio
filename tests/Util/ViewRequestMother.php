<?php

namespace Tests\Util;

use App\Domain\ViewRequest\CreateViewRequestDto;
use App\Domain\ViewRequest\GetViewRequestDto;
use App\Domain\ViewRequest\UpdateViewRequestDto;
use App\Domain\ViewRequest\ViewRequestEntity;
use App\Domain\ViewRequest\ViewRequestPageDto;
use App\Domain\ViewRequest\ViewRequestStatus;
use Carbon\Carbon;

class ViewRequestMother
{
    public const DEFAULT_ID = 1;
    public const DEFAULT_PROPERTY_ID = 1;
    public const DEFAULT_USER_ID = 1;
    public const DEFAULT_DATE = '2024-10-23';
    public const DEFAULT_STATUS = ViewRequestStatus::OPEN;

    public static function defaultCreateViewRequestDto(): CreateViewRequestDto
    {
        return new CreateViewRequestDto(
            Carbon::parse(self::DEFAULT_DATE),
            self::DEFAULT_PROPERTY_ID,
            self::DEFAULT_USER_ID
        );
    }

    public static function defaultGetViewRequestDto(): GetViewRequestDto
    {
        return new GetViewRequestDto(
            1,
            10,
            self::DEFAULT_USER_ID
        );
    }

    public static function defaultUpdateViewRequestDto(int $id): UpdateViewRequestDto
    {
        return new UpdateViewRequestDto(
            $id,
            Carbon::parse(self::DEFAULT_DATE),
            self::DEFAULT_STATUS
        );
    }

    public static function defaultViewRequestPageDto(): ViewRequestPageDto
    {
        return new ViewRequestPageDto(
            100,
            1,
            10,
            []
        );
    }

    public static function defaultViewRequestEntity(): ViewRequestEntity
    {
        return new ViewRequestEntity(
            self::DEFAULT_ID,
            self::DEFAULT_STATUS,
            Carbon::parse(self::DEFAULT_DATE),
            self::DEFAULT_PROPERTY_ID,
            self::DEFAULT_USER_ID
        );
    }

    public static function viewRequestEntityWithParams(
        ?int $id = null,
        ViewRequestStatus $status = self::DEFAULT_STATUS,
        ?Carbon $date = null,
        int $propertyId = self::DEFAULT_PROPERTY_ID,
        int $userId = self::DEFAULT_USER_ID
    ): ViewRequestEntity {
        return new ViewRequestEntity(
            $id ?? self::DEFAULT_ID,
            $status,
            $date ?? Carbon::parse(self::DEFAULT_DATE),
            $propertyId,
            $userId
        );
    }
}

