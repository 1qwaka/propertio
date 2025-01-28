<?php

namespace App\Domain\Property;

use App\Domain\Agent\AgentEntity;

class PropertyEntity
{
    public function __construct(
        public ?int $id,
        public ?string $renovation,
        public ?int $buildingId,
        public int $floor,
        public ?float $area,
        public ?int $floorTypeId,
        public string $address,
        public LivingSpaceType $livingSpaceType,
        public int $agentId,
    )
    {
    }
}
