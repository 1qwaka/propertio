<?php

namespace App\Domain\User;

interface IUserService
{
    public function getSelf(): UserEntity;
    public function create(CreateUserDto $data): UserEntity;
    public function login(LoginUserDto $data): UserEntity;
    public function logout(): void;
}
