<?php

namespace App\Domain\Advertisement;

class CreateAdvertisementDto
{
    public function __construct(
        public int $price,
        public int $propertyId,
        public AdvertisementStatus $type,
        public ?bool $hidden = false,
        public ?string $description = null,
        public ?int $agentId = null,
    )
    {
    }
}
