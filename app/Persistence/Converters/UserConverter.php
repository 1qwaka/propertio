<?php
namespace App\Persistence\Converters;

use App\Domain\User\UserEntity;
use App\Models\User;

class UserConverter
{
    public static function toDomain(User $model): UserEntity
    {
        return new UserEntity(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: $model->password,
        );
    }

    public static function fromDomain(UserEntity $entity): User
    {
        return new User([
            'id' => $entity->id,
            'name' => $entity->name,
            'email' => $entity->email,
            'password' => $entity->password,
        ]);
    }
}
