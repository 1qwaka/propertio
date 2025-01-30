<?php

namespace App\Persistence\Repository;

use App\Domain\User\CreateUserDto;
use App\Domain\User\IUserRepository;
use App\Domain\User\LoginUserDto;
use App\Domain\User\UserEntity;
use App\Exceptions\WithErrorCodeException;
use App\Models\User;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Converters\UserConverter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{

    public function find(int $id): UserEntity
    {
        $user = User::find($id);
        if (!$user) {
            throw new ModelNotFoundException('User not found');
        }
        return UserConverter::toDomain($user);

    }

    public function create(CreateUserDto $data): UserEntity
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
        Auth::login($user);
        return UserConverter::toDomain($user);
    }

    public function login(LoginUserDto $data): UserEntity
    {
        if (Auth::attempt(DtoToModelConverter::toArray($data))) {
            $user = User::find(Auth::id());
            return UserConverter::toDomain($user);
        }
        throw new WithErrorCodeException('Credentials invalid', 401);
    }

    public function logout(): void
    {
        $user = Auth::user();
        if ($user == null) {
            throw new WithErrorCodeException('You are not logged in', 401);
        }

        Auth::logout();
    }


}
