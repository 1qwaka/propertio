<?php

namespace Tests\Integration;


use App\Models\Agent;
use App\Models\User;
use App\Persistence\Repository\AgentRepository;
use App\Persistence\Repository\UserRepository;
use App\Services\AgentService;
use App\Services\UserService;
use Database\Seeders\AgentTypeSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\Epic;
use Tests\TestCase;
use Tests\Util\AgentMother;

class AgentTest extends TestCase
{
    use DatabaseTruncation;

    private AgentService $service;

    protected function setUp(): void
    {
        if (env('CI_SKIP') == 'true') {
            Allure::description("Test Skipped due to CI pipeline flag CI_SKIP=true");
            $this->fail("Skipped due to CI pipeline flag CI_SKIP=true");
        }
        parent::setUp();

        $agentRepository = new AgentRepository();
        $userRepository = new UserRepository();
        $userService = new UserService($userRepository);
        $this->service = new AgentService($agentRepository, $userService);
    }

    #[Epic('Integration')]
    public function testCreate(): void
    {
        $this->seed(AgentTypeSeeder::class);
        $user = User::factory()->create();
        Auth::login($user);
        $data = AgentMother::regularCreateAgentDto();

        $result = $this->service->create($data);

        $this->assertEquals($user->id, $result->userId);
        $this->assertDatabaseHas('agents', [
            'user_id' => $user->id,
        ]);
    }

    #[Epic('Integration')]
    public function testGetSelf(): void
    {
        $this->seed(AgentTypeSeeder::class);
        $user = User::factory()->create();
        Auth::login($user);
        $agent = Agent::factory()->create(['user_id' => $user->id]);

        $result = $this->service->getSelf();

        $this->assertEquals($agent->id, $result->id);
    }

    #[Epic('Integration')]
    public function testGetTypes(): void
    {
        $agentTypes = [
            ['name' => 'Физическое лицо'],
            ['name' => 'Юридическое лицо'],
            ['name' => 'Риелтор'],
            ['name' => 'Застройщик'],
            ['name' => 'Агенство'],
            ['name' => 'Собственник'],
        ];
        DB::table('agent_type')->insert($agentTypes);

        $result = $this->service->getTypes();

        foreach ($agentTypes as $agentType) {
            $this->assertCount(1, array_filter(
                $result,
                fn($type) => $type->name === $agentType['name']
            ));
        }
    }

    #[Epic('Integration')]
    public function testUpdate(): void
    {
        $this->seed(AgentTypeSeeder::class);
        $user = User::factory()->create();
        Auth::login($user);
        $agent = Agent::factory()->create(['user_id' => $user->id]);
        $data = AgentMother::regularUpdateAgentDto();
        $data->id = $agent->id;
        $data->name = 'gigachad agent';

        $result = $this->service->update($data);

        $this->assertDatabaseHas('agents', [
            'id' => $data->id,
            'name' => $data->name,
        ]);
    }
}
