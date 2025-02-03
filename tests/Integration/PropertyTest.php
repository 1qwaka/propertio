<?php

namespace Tests\Integration;


use App\Models\Agent;
use App\Models\Property;
use App\Models\User;
use App\Persistence\Converters\DtoToModelConverter;
use App\Persistence\Repository\AgentRepository;
use App\Persistence\Repository\PropertyRepository;
use App\Persistence\Repository\UserRepository;
use App\Services\AgentService;
use App\Services\PropertyService;
use App\Services\UserService;
use Database\Seeders\AgentTypeSeeder;
use Database\Seeders\BuildingSeeder;
use Database\Seeders\BuildingTypeSeeder;
use Database\Seeders\DeveloperSeeder;
use Database\Seeders\FloorTypeSeeder;
use Database\Seeders\PropertySeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Auth;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\Epic;
use Tests\TestCase;
use Tests\Util\PropertyMother;
use Tests\Util\TestUtil;

class PropertyTest extends TestCase
{
    use DatabaseTruncation;

    private PropertyService $service;

    private User $user;

    private Agent $agent;

    protected function setUp(): void
    {
        parent::setUp();

        $agentRepository = new AgentRepository();
        $propertyRepository = new PropertyRepository();
        $userRepository = new UserRepository();

        $userService = new UserService($userRepository);
        $agentService = new AgentService($agentRepository, $userService);
        $this->service = new PropertyService($propertyRepository, $agentService);

        $this->seed([
            AgentTypeSeeder::class,
            FloorTypeSeeder::class,
            BuildingTypeSeeder::class,
            DeveloperSeeder::class,
            BuildingSeeder::class,
        ]);
        $this->user = User::factory()->create();
        Auth::login($this->user);
        $this->agent = Agent::factory()->create(['user_id' => $this->user->id]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (env('CI_SKIP') == 'true') {
            Allure::description("Test Skipped due to CI pipeline flag CI_SKIP=true");
            $this->fail("Skipped due to CI pipeline flag CI_SKIP=true");
        }
    }
    #[Epic('Integration')]
    public function testCreate(): void
    {
        $data = PropertyMother::defaultCreatePropertyDto();
        $data->floor = 1;

        $item = $this->service->create($data);

        $this->assertDatabaseHas('properties', [
            'id' => $item->id,
            'agent_id' => $this->agent->id,
        ]);
    }

    #[Epic('Integration')]
    public function testFind(): void
    {
        $property = Property::factory()->create();

        $result = $this->service->find($property->id);

        $this->assertEquals($property->id, $result->id);
    }

    #[Epic('Integration')]
    public function testGetFloorTypes(): void
    {
        $result = $this->service->getFloorTypes();
        $this->assertIsArray($result);
    }

    #[Epic('Integration')]
    public function testGetSpaceTypes(): void
    {
        $result = $this->service->getSpaceTypes();
        $this->assertContains('primary', TestUtil::objToArray($result));
        $this->assertContains('secondary', TestUtil::objToArray($result));
    }

    #[Epic('Integration')]
    public function testUpdate(): void
    {
//        $user = User::factory()->create();
//        Auth::login($user);
//        $agent = Agent::factory()->create(['user_id' => $user->id]);
        $property = Property::factory()->create(['agent_id' => $this->agent->id]);
        $data = PropertyMother::defaultUpdatePropertyDto();
        $data->id = $property->id;
        $data->floor = null;

        $result = $this->service->update($data);

        $this->assertEquals($property->id, $result->id);
        $this->assertDatabaseHas('properties', [
            'id' => $data->id,
        ]);
    }

    #[Epic('Integration')]
    public function testDelete(): void
    {
//        $user = User::factory()->create();
//        Auth::login($user);
//        $agent = Agent::factory()->create(['user_id' => $user->id]);
        $property = Property::factory()->create(['agent_id' => $this->agent->id]);

        $this->service->delete($property->id);

        $this->assertDatabaseMissing('properties', [
            'id' => $property->id,
        ]);
    }
}
