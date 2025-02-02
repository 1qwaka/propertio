<?php

namespace App\Domain\Agent;

class CreateAgentDto
{
    public function __construct(
        public int $typeId,
        public string $name,
        public string $address,
        public string $email,
        public ?int $userId = null,
    )
    {
    }
}
