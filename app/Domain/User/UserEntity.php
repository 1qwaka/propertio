<?php

namespace App\Domain\User;

class UserEntity
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $password,
    ) {

    }
}
