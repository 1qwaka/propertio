<?php

namespace App\Services;

use App\Domain\User\CreateUserDto;
use App\Domain\User\IUserService;
use App\Domain\User\LoginUserDto;
use App\Domain\User\UserEntity;
use App\Models\User;
use App\Persistence\Converters\UserConverter;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    public function getSelf(): UserEntity
    {
        $user = User::find(Auth::id());
        return UserConverter::toDomain($user instanceof User ? $user : null);
    }

    public function create(CreateUserDto $data): UserEntity
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
        $user->save();
        Auth::login($user);
        return UserConverter::toDomain($user);
    }

    /**
     * @throws Exception
     */
    public function login(LoginUserDto $data): UserEntity
    {
        if (Auth::attempt(['email' => $data->email, 'password' => $data->password])) {
            $user = User::find(Auth::id());
            return UserConverter::toDomain($user instanceof User ? $user : null);
        }
        throw new Exception('Credentials invalid');
    }

    /**
     * @throws Exception
     */
    public function logout(): void
    {
        $user = Auth::user();
        if ($user == null) {
            throw new Exception('You are not logged in');
        }

        Auth::logout();
    }
}
