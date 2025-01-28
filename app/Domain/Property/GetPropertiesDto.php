<?php

namespace App\Domain\Property;

readonly class GetPropertiesDto
{
    public function __construct(
        public ?int $page = 1,
        public ?int $perPage = 10,
        public ?int $agentId = null,
        public ?LivingSpaceType $livingSpaceType = null,
    )
    {
    }
}
