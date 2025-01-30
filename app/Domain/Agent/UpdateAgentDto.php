<?php

namespace App\Domain\Agent;

class UpdateAgentDto
{
    public function __construct(
        public ?int $id = null,
        public ?int $typeId = null,
        public ?string $name = null,
        public ?string $address = null,
        public ?string $email = null,
    )
    {
    }
}
