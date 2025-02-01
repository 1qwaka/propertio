<?php

namespace Tests\Util;

use App\Domain\Advertisement\AdvertisementEntity;
use App\Domain\Advertisement\AdvertisementPageDto;
use App\Domain\Advertisement\AdvertisementStatus;
use App\Domain\Advertisement\CreateAdvertisementDto;
use App\Domain\Advertisement\GetAdvertisementsDto;
use App\Domain\Advertisement\UpdateAdvertisementDto;

class AdvertisementMother
{
    public static function createAdvertisementDto(): CreateAdvertisementDto
    {
        return new CreateAdvertisementDto(
            price: 100000,
            propertyId: 1,
            type: AdvertisementStatus::SELL,
            hidden: false,
            description: 'Prodaetsa',
            agentId: 10
        );
    }

    public static function updateAdvertisementDto(): UpdateAdvertisementDto
    {
        return new UpdateAdvertisementDto(
            id: 1,
            description: 'Some description',
            price: 110000,
            type: AdvertisementStatus::SELL,
            hidden: false
        );
    }

    public static function getAdvertisementsDto(): GetAdvertisementsDto
    {
        return new GetAdvertisementsDto(
            page: 1,
            perPage: 10,
            agentId: 10,
            hidden: false
        );
    }

    public static function advertisementEntity(): AdvertisementEntity
    {
        return new AdvertisementEntity(
            id: 1,
            agentId: 10,
            description: 'Prodaetsa',
            price: 100000,
            propertyId: 1,
            type: AdvertisementStatus::SELL,
            hidden: false
        );
    }

    public static function advertisementPageDto(): AdvertisementPageDto
    {
        return new AdvertisementPageDto(
            total: 1,
            current: 1,
            perPage: 10,
            items: [self::advertisementEntity()]
        );
    }
}
