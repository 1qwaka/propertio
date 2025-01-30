<?php

namespace App\Persistence\Repository;

use App\Domain\Agent\AgentEntity;
use App\Domain\Agent\AgentStatsDto;
use App\Domain\Agent\CreateAgentDto;
use App\Domain\Agent\IAgentRepository;
use App\Domain\Agent\UpdateAgentDto;
use App\Models\Agent;
use App\Persistence\Converters\AgentConverter;
use App\Persistence\Converters\DtoToModelConverter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AgentRepository implements IAgentRepository
{

    public function getTypes(): array
    {
        return DB::table('agent_type')->get()->toArray();
    }

    public function findByUser(int $id): AgentEntity
    {
        $item = Agent::find($id);
        if (!$item) {
            throw new ModelNotFoundException('Agent not found');
        }
        return AgentConverter::toDomain($item);
    }

//    public function getStats(int $id): AgentStatsDto
//    {
//        $count = DB::select('select count_accepted_contracts(?) as total', [$id]);
//        return new AgentStatsDto($count);
//    }

    public function create(CreateAgentDto $data): AgentEntity
    {
        $agent = Agent::create(DtoToModelConverter::toArray($data));
        return AgentConverter::toDomain($agent);
    }

    public function update(UpdateAgentDto $data): AgentEntity
    {
        $agent = Agent::find($data->id);

        if (!$agent) {
            throw new ModelNotFoundException("Agent not found");
        }

        $agent->update(DtoToModelConverter::toArray($data));
        return AgentConverter::toDomain($agent);
    }
}
