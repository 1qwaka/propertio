<?php

namespace App\Domain\Property;

class CreatePropertyDto
{
    public function __construct(
        public int $buildingId,
        public int $floor,
        public int $floorTypeId,
        public string $address,
        public LivingSpaceType $livingSpaceType,
        public ?string $renovation = null,
        public ?int $area = null,
        public ?int $agentId = null,
    )
    {
    }
}
