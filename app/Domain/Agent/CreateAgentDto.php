<?php

namespace App\Domain\Agent;

readonly class CreateAgentDto
{
    public function __construct(
        public int $typeId,
        public string $name,
        public string $address,
        public string $email,
    )
    {
    }
}
