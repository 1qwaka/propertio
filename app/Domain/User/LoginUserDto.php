<?php

namespace App\Domain\User;

class LoginUserDto
{
    public function __construct(
        public string $email,
        public string $password,
    )
    {
    }
}
