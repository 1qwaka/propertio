<?php

namespace App\Domain\Agent;

use App\Domain\User\UserEntity;

class AgentEntity
{
    public function __construct(
        public ?int $id,
        public ?int $typeId,
        public string $name,
        public string $address,
        public string $email,
        public int $userId,
    )
    {
    }
}
