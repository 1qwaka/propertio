<?php

namespace App\Domain\User;

interface IUserService
{
    public function getSelf(): UserEntity;
    public function create(CreateUserDto $data): UserEntity;
    public function login(LoginUserDto $data): UserEntity;
    public function login2(LoginUserDto $data): void;
    public function logout(): void;

    public function confirm(LoginUserDto $data, string $code): UserEntity;
}
