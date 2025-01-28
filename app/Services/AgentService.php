<?php

namespace App\Services;

use App\Domain\Agent\AgentEntity;
use App\Domain\Agent\AgentType;
use App\Domain\Agent\CreateAgentDto;
use App\Domain\Agent\IAgentService;
use App\Domain\Agent\UpdateAgentDto;
use App\Models\Agent;
use App\Persistence\Converters\AgentConverter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentService implements IAgentService
{

    public function getSelf(): AgentEntity
    {
        $agent = Auth::user()->agent;
        return AgentConverter::toDomain($agent);
    }

    public function getTypes(): array
    {
        $items = DB::table('agent_type')->get();
        return $items->toArray();
    }

    public function getStats(): array
    {
        $agent = Agent::find('user_id', Auth::user()->id)->get()->first();
        $count = DB::select('select count_accepted_contracts(?) as total', [$agent->id]);
        return compact('count');
    }

    public function create(CreateAgentDto $data): AgentEntity
    {
        $user = Auth::user();
        $agent = Agent::create([
            'type_id' => $data->typeId,
            'name' => $data->name,
            'address' => $data->address,
            'email' => $data->email,
            'user_id' => $user->id,
        ]);
        $agent->save();
        return AgentConverter::toDomain($agent);
    }

    public function update(UpdateAgentDto $data): AgentEntity
    {
        $agent = Agent::find('user_id', Auth::user()->id)->get()->first();

        if ($agent == null) {
            throw new \Exception("You are not an agent");
        }

        $agent->update(array_filter([
            'type_id' => $data->typeId,
            'email' => $data->email,
            'name' => $data->name,
            'address' => $data->address,
        ]));

        return AgentConverter::toDomain($agent);
    }
}
