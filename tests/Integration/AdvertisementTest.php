<?php

namespace Tests\Integration;


use App\Models\Advertisement;
use App\Models\Agent;
use App\Models\Property;
use App\Models\User;
use App\Persistence\Repository\AdvertisementRepository;
use App\Persistence\Repository\AgentRepository;
use App\Persistence\Repository\PropertyRepository;
use App\Persistence\Repository\UserRepository;
use App\Services\AdvertisementService;
use App\Services\AgentService;
use App\Services\PropertyService;
use App\Services\UserService;
use Database\Seeders\AgentTypeSeeder;
use Database\Seeders\BuildingSeeder;
use Database\Seeders\BuildingTypeSeeder;
use Database\Seeders\DeveloperSeeder;
use Database\Seeders\FloorTypeSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Auth;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\Epic;
use Tests\TestCase;
use Tests\Util\AdvertisementMother;
use Tests\Util\TestUtil;

class AdvertisementTest extends TestCase
{
    use DatabaseTruncation;

    private AdvertisementService $service;

    private User $user;

    private Agent $agent;

    private Property $property;

    protected function setUp(): void
    {
        if (env('CI_SKIP') == 'true') {
            Allure::description("Test Skipped due to CI pipeline flag CI_SKIP=true");
            $this->fail("Skipped due to CI pipeline flag CI_SKIP=true");
        }
        parent::setUp();

        $agentRepository = new AgentRepository();
        $propertyRepository = new PropertyRepository();
        $advertisementRepository = new AdvertisementRepository();
        $userRepository = new UserRepository();

        $userService = new UserService($userRepository);
        $agentService = new AgentService($agentRepository, $userService);
        $propertyService = new PropertyService($propertyRepository, $agentService);
        $this->service = new AdvertisementService($advertisementRepository, $agentService, $propertyService);

        $this->seed([
            AgentTypeSeeder::class,
            FloorTypeSeeder::class,
            DeveloperSeeder::class,
            BuildingTypeSeeder::class,
            BuildingSeeder::class,
        ]);
        $this->user = User::factory()->create();
        Auth::login($this->user);
        $this->agent = Agent::factory()->create(['user_id' => $this->user->id]);
        $this->property = Property::factory()->create(['agent_id' => $this->agent->id]);
    }

    #[Epic('Integration')]
    public function testCreate(): void
    {
        $data = AdvertisementMother::createAdvertisementDto();
        $data->propertyId = $this->property->id;
        $data->agentId = $this->agent->id;

        $result = $this->service->create($data);

        $this->assertEquals($data->agentId, $result->agentId);
        $this->assertEquals($data->propertyId, $result->propertyId);
        $this->assertDatabaseHas('advertisements', [
            'id' => $result->id,
            'agent_id' => $this->agent->id,
            'property_id' => $this->property->id,
        ]);
    }

    #[Epic('Integration')]
    public function testFind(): void
    {
        $advertisement = Advertisement::factory()->create([
            'agent_id' => $this->agent->id,
            'property_id' => $this->property->id,
        ]);

        $result = $this->service->find($advertisement->id);

        $this->assertEquals($advertisement->id, $result->id);
        $this->assertEquals($advertisement->property_id, $result->propertyId);
        $this->assertEquals($advertisement->agent_id, $result->agentId);
        $this->assertEquals($advertisement->type, $result->type->value);
        $this->assertEquals($advertisement->hidden, $result->hidden);
        $this->assertEquals($advertisement->description, $result->description);
        $this->assertEquals($advertisement->price, $result->price);
    }

    #[Epic('Integration')]
    public function testGet(): void
    {
        $expectedCount = 5;
        $data = AdvertisementMother::getAdvertisementsDto();
        $data->agentId = null;
        Advertisement::factory($expectedCount)->create([
            'hidden' => false,
        ]);

        $result = $this->service->get($data);

        $this->assertEquals($data->page, $result->current);
        $this->assertEquals($data->perPage, $result->perPage);
        $this->assertEquals($expectedCount, $result->total);
        $this->assertCount($expectedCount, $result->items);
    }

    #[Epic('Integration')]
    public function testUpdate(): void
    {
        $advertisement = Advertisement::factory()->create(['agent_id' => $this->agent->id]);
        $data = AdvertisementMother::updateAdvertisementDto();
        $data->id = $advertisement->id;
        $data->description = 'aaaaaaaaaaaaaoooooooooooooooooooeeeeee';

        $result = $this->service->update($data);

        $this->assertEquals($advertisement->id, $result->id);
        $this->assertDatabaseHas('advertisements', [
            'id' => $data->id,
            'description' => $data->description,
        ]);
    }

    #[Epic('Integration')]
    public function testDelete(): void
    {
        $advertisement = Advertisement::factory()->create(['agent_id' => $this->agent->id]);

        $this->service->delete($advertisement->id);

        $this->assertDatabaseMissing('advertisements', [
            'id' => $advertisement->id,
        ]);
    }
}
