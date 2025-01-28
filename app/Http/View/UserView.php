<?php

namespace App\Http\View;

use App\Domain\User\UserEntity;

class UserView
{
    public const ID = 'id';
    public const NAME = 'name';
    public const EMAIL = 'email';

    public static function toArray(UserEntity $ent): array
    {
        return [
            self::ID => $ent->id,
            self::NAME => $ent->name,
            self::EMAIL => $ent->email,
        ];
    }
}
