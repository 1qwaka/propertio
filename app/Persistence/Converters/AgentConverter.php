<?php

namespace App\Persistence\Converters;

use App\Domain\Agent\AgentEntity;
use App\Models\Agent;

class AgentConverter
{
    public static function toDomain(Agent $model): AgentEntity
    {

        return new AgentEntity(
            id: $model->id,
            typeId: $model->type_id,
            name: $model->name,
            address: $model->address,
            email: $model->email,
            userId: $model->user_id,
        );
    }

    public static function fromDomain(AgentEntity $entity): Agent
    {
        return new Agent([

        ]);
    }
}
