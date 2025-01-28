<?php

namespace App\Domain\Agent;

class UpdateAgentDto
{
    public function __construct(
        public ?int $typeId,
        public ?string $name,
        public ?string $address,
        public ?string $email,
    )
    {
    }
}
