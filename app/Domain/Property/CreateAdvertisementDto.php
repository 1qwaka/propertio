<?php

namespace App\Domain\Property;

use App\Domain\Advertisement\AdvertisementStatus;

readonly class CreateAdvertisementDto
{
    public function __construct(
        public int $price,
        public int $propertyId,
        public AdvertisementStatus $type,
        public ?bool $hidden = false,
        public ?string $description = null,
    )
    {
    }
}
