<?php

namespace App\Services;

use App\Domain\User\CreateUserDto;
use App\Domain\User\IUserRepository;
use App\Domain\User\IUserService;
use App\Domain\User\LoginUserDto;
use App\Domain\User\UserEntity;
use App\Exceptions\WithErrorCodeException;
use App\Mail\VerifyCodeMail;
use App\Models\User;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Converters\UserConverter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function login2(LoginUserDto $data): void
    {
        $user = (new \App\Models\User)->where('email', $data->email)->get()->first();

        if (!($user && Hash::check($data->password, $user->password))) {
            throw new WithErrorCodeException('Credentials invalid', 401);
        }

        $code = Str::random(2);
        Mail::to($data->email)->send(new VerifyCodeMail($code));

        DB::insert("insert into auth_codes (user_id, email, code) values (?, ?, ?)",
            [ $user->id, $data->email, $code ]);
    }

    public function confirm(LoginUserDto $data, string $code): UserEntity
    {
        $authCode = DB::table('auth_codes')->where('email', $data->email)->first();

        if ($code != $authCode->code) {
            throw new WithErrorCodeException('Code invalid', 401);
        }

        if (!Auth::attempt(DtoToModelConverter::toArray($data))) {
            throw new WithErrorCodeException('Auth failed', 401);
        }
        DB::table('auth_codes')->where('email', $data->email)->delete();

        $user = User::find(Auth::id());
        return UserConverter::toDomain($user);
    }


}
