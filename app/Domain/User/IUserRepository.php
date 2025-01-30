<?php

namespace App\Domain\User;

interface IUserRepository
{
    public function find(int $id): UserEntity;
    public function create(CreateUserDto $data): UserEntity;
    public function login(LoginUserDto $data): UserEntity;
    public function logout(): void;
}
