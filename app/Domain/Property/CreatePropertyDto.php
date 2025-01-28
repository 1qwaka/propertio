<?php

namespace App\Domain\Property;

readonly class CreatePropertyDto
{
    public function __construct(
        public ?string $renovation = null,
        public int $buildingId,
        public int $floor,
        public ?int $area = null,
        public int $floorTypeId,
        public string $address,
        public LivingSpaceType $livingSpaceType,
    )
    {
    }
}
