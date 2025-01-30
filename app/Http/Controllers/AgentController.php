<?php

namespace App\Http\Controllers;

use App\Domain\Agent\CreateAgentDto;
use App\Domain\Agent\IAgentService;
use App\Domain\Agent\UpdateAgentDto;
use App\Http\View\AgentView;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    public function __construct(
        private IAgentService $agentService,
    )
    {
    }

    public function types(Request $request)
    {
        return response()->json([
            'message' => 'Success',
            'items' => $this->agentService->getTypes(),
        ]);
    }

    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'typeId' => 'required|int|max:255|exists:agent_type,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:agents'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Registration failed',
                'errors' => $validated->errors()
            ], 400);
        }

        $data = new CreateAgentDto(...$validated->safe()->all());

        return response()->json([
            'message' => 'Success',
            'agent' => $this->agentService->create($data)
        ]);
    }

    public function update(Request $request)
    {
        $validated = Validator::make($request->only(['type', 'name', 'address', 'email']), [
            'typeId' => 'string|max:255',
            'name' => 'string|max:255',
            'address' => 'string|max:255',
            'email' => ['string', 'email', 'max:255'],
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message' => 'Error',
                'errors' => $validated->errors()
            ], 400);
        }

        $data = new UpdateAgentDto(...$validated->safe()->all());
        return response()->json([
            'message' => 'Success',
            'item' => $this->agentService->update($data),
        ]);
    }

//    public function stats(Request $request)
//    {
//        $res = $this->agentService->getStats();
//        return response()->json([
//            'message' => 'Success',
//            'count' => $res['count'],
//        ]);
//    }

    public function self(Request $request)
    {
        $agent = $this->agentService->getSelf();
        return response()->json([
            'message' => 'Success',
            'item' => $agent,
        ]);
    }
}
