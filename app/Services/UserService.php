<?php

namespace App\Services;

use App\Domain\User\CreateUserDto;
use App\Domain\User\IUserRepository;
use App\Domain\User\IUserService;
use App\Domain\User\LoginUserDto;
use App\Domain\User\UserEntity;
use Illuminate\Support\Facades\Auth;

class UserService implements IUserService
{
    public function __construct(
        private readonly IUserRepository $repository,
    )
    {
    }

    public function getSelf(): UserEntity
    {
        return $this->repository->find(Auth::id());
    }

    public function create(CreateUserDto $data): UserEntity
    {
        return $this->repository->create($data);
    }

    public function login(LoginUserDto $data): UserEntity
    {
        return $this->repository->login($data);
    }

    public function logout(): void
    {
        $this->repository->logout();
    }
}
