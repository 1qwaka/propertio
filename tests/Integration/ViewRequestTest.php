<?php

namespace Tests\Integration;


use App\Domain\ViewRequest\ViewRequestStatus;
use App\Models\Agent;
use App\Models\Property;
use App\Models\User;
use App\Models\ViewRequest;
use App\Persistence\Repository\AgentRepository;
use App\Persistence\Repository\PropertyRepository;
use App\Persistence\Repository\UserRepository;
use App\Persistence\Repository\ViewRequestRepository;
use App\Services\AgentService;
use App\Services\PropertyService;
use App\Services\UserService;
use App\Services\ViewRequestService;
use Database\Seeders\AgentTypeSeeder;
use Database\Seeders\BuildingSeeder;
use Database\Seeders\BuildingTypeSeeder;
use Database\Seeders\DeveloperSeeder;
use Database\Seeders\FloorTypeSeeder;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Auth;
use Qameta\Allure\Attribute\Epic;
use Tests\TestCase;
use Tests\Util\ViewRequestMother;

class ViewRequestTest extends TestCase
{
    use DatabaseTruncation;

    private ViewRequestService $service;
    private UserService $userService;
    private AgentService $agentService;
    private PropertyService $propertyService;

    private User $user;

    private User $customerUser;

    private Agent $agent;

    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();

        $viewRequestRepository = new ViewRequestRepository();
        $userRepository = new UserRepository();
        $agentRepository = new AgentRepository();
        $propertyRepository = new PropertyRepository();

        $this->userService = new UserService($userRepository);
        $this->agentService = new AgentService($agentRepository, $this->userService);
        $this->propertyService = new PropertyService($propertyRepository, $this->agentService);
        $this->service = new ViewRequestService(
            $viewRequestRepository,
            $this->userService,
            $this->agentService,
            $this->propertyService
        );

        $this->seed([
            AgentTypeSeeder::class,
            FloorTypeSeeder::class,
            DeveloperSeeder::class,
            BuildingTypeSeeder::class,
            BuildingSeeder::class,
        ]);
        $this->user = User::factory()->create();
        $this->customerUser = User::factory()->create();
        Auth::login($this->user);
        $this->agent = Agent::factory()->create(['user_id' => $this->user->id]);
        $this->property = Property::factory()->create(['agent_id' => $this->agent->id]);
        Auth::logout();
    }

    #[Epic('Integration')]
    public function testCreate(): void
    {
        Auth::login($this->customerUser);
        $data = ViewRequestMother::defaultCreateViewRequestDto();
        $data->propertyId = $this->property->id;

        $result = $this->service->create($data);

        $this->assertDatabaseHas('view_requests', [
            'id' => $result->id,
            'user_id' => $this->customerUser->id,
            'property_id' => $this->property->id,
        ]);
    }

    #[Epic('Integration')]
    public function testFind(): void
    {
        Auth::login($this->customerUser);
        $viewRequest = ViewRequest::factory()->create(['user_id' => $this->customerUser->id]);

        $result = $this->service->find($viewRequest->id);

        $this->assertEquals($viewRequest->id, $result->id);
        $this->assertEquals($viewRequest->property_id, $result->propertyId);
        $this->assertEquals($viewRequest->user_id, $result->userId);
        $this->assertEquals($viewRequest->status, $result->status->value);
        $this->assertTrue($result->date->equalTo($viewRequest->date));
    }

    #[Epic('Integration')]
    public function testGetAgent(): void
    {
        $expectCount = 3;
        Auth::login($this->customerUser);
        ViewRequest::factory($expectCount)->create([
            'user_id' => $this->customerUser->id,
            'property_id' => $this->property->id,
        ]);

        Auth::login($this->user);

        $data = ViewRequestMother::defaultGetViewRequestDto();

        $result = $this->service->getAgent($data);

        $this->assertEquals($expectCount, $result->total);
        $this->assertCount($expectCount, $result->items);
    }

    #[Epic('Integration')]
    public function testGetUser(): void
    {
        $expectCount = 3;
        Auth::login($this->customerUser);
        ViewRequest::factory($expectCount)->create([
            'user_id' => $this->customerUser->id,
        ]);

        $data = ViewRequestMother::defaultGetViewRequestDto();

        $result = $this->service->getUser($data);

        $this->assertEquals($expectCount, $result->total);
        $this->assertCount($expectCount, $result->items);
    }

    #[Epic('Integration')]
    public function testUpdateDate(): void
    {
        Auth::login($this->customerUser);
        $viewRequest = ViewRequest::factory()->create([
            'user_id' => $this->customerUser->id,
            'status' => ViewRequestStatus::OPEN,
        ]);
        $newDate = now()->addDays(2);

        $result = $this->service->updateDate($viewRequest->id, $newDate);

        $this->assertEquals($viewRequest->id, $result->id);
        $this->assertTrue($newDate->equalTo($result->date));
    }

    #[Epic('Integration')]
    public function testUpdateStatus(): void
    {
        Auth::login($this->customerUser);
        $viewRequest = ViewRequest::factory()->create([
            'status' => ViewRequestStatus::OPEN,
        ]);

        Auth::login($this->user);
        $newStatus = ViewRequestStatus::ACCEPTED;

        $result = $this->service->updateStatus($viewRequest->id, $newStatus);

        $this->assertEquals($viewRequest->id, $result->id);
        $this->assertEquals($newStatus, $result->status);
    }

    #[Epic('Integration')]
    public function testDelete(): void
    {
        Auth::login($this->customerUser);
        $viewRequest = ViewRequest::factory()->create(['user_id' => $this->customerUser->id]);

        $this->service->delete($viewRequest->id);

        $this->assertDatabaseMissing('view_requests', [
            'id' => $viewRequest->id,
        ]);
    }
}
