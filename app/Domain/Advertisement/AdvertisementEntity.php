<?php

namespace App\Domain\Advertisement;

use App\Domain\Agent\AgentEntity;
use App\Domain\Property\PropertyEntity;

class AdvertisementEntity
{
    public function __construct(
        public ?int $id,
        public int $agentId,
        public ?string $description,
        public int $price,
        public int $propertyId,
        public AdvertisementStatus $type,
        public ?bool $hidden,
    )
    {
    }
}

