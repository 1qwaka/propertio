<?php

namespace App\Domain\Building;

class BuildingEntity
{
    public function __construct(
        public ?int $id,
        public int $typeId,
        public ?bool $hotWater,
        public ?bool $gas,
        public ?int $elevators,
        public int $floors,
        public int $buildYear,
        public int $developerId,
        public string $address,
    )
    {
    }
}
