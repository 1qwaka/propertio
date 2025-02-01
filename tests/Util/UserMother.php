<?php

namespace Tests\Util;

use App\Domain\User\CreateUserDto;
use App\Domain\User\LoginUserDto;
use App\Domain\User\UserEntity;

class UserMother
{
    public const DEFAULT_ID = 1;
    public const DEFAULT_NAME = 'Regular user';
    public const DEFAULT_EMAIL = 'user123@example.com';
    public const DEFAULT_PASSWORD = 'password';

    public static function regularCreateUserDto(): CreateUserDto
    {
        return new CreateUserDto(
            self::DEFAULT_NAME,
            self::DEFAULT_EMAIL,
            self::DEFAULT_PASSWORD
        );
    }

    public static function regularLoginUserDto(): LoginUserDto
    {
        return new LoginUserDto(self::DEFAULT_EMAIL, self::DEFAULT_PASSWORD);
    }

    public static function regularUserEntity(): UserEntity
    {
        return new UserEntity(
            self::DEFAULT_ID,
            self::DEFAULT_NAME,
            self::DEFAULT_EMAIL,
            self::DEFAULT_PASSWORD
        );
    }
}
