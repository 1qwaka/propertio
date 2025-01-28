<?php

namespace App\Http\View;

use App\Domain\Agent\AgentEntity;

class AgentView
{
    public const ID = 'id';
    public const TYPE_ID = 'typeId';
    public const NAME = 'name';
    public const ADDRESS = 'address';
    public const EMAIL = 'email';
    public const USER_ID = 'userId';

    public static function toArray(AgentEntity $ent): array
    {
        return [
            self::ID => $ent->id,
            self::TYPE_ID => $ent->typeId,
            self::NAME => $ent->name,
            self::ADDRESS => $ent->address,
            self::EMAIL => $ent->email,
            self::USER_ID => $ent->userId,
        ];
    }
}
