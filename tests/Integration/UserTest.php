<?php

namespace Tests\Integration;

use App\Models\User;
use App\Persistence\Repository\UserRepository;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\Epic;
use Tests\TestCase;
use Tests\Util\UserMother;

class UserTest extends TestCase
{
    use DatabaseTruncation;

    private UserService $service;

    protected function setUp(): void
    {
        if (env('CI_SKIP') == 'true') {
            Allure::description("Test Skipped due to CI pipeline flag CI_SKIP=true");
            $this->fail("Skipped due to CI pipeline flag CI_SKIP=true");
        }
        parent::setUp();

        $repository = new UserRepository();
        $this->service = new UserService($repository);
    }

    #[Epic('Integration')]
    public function testCreate(): void
    {
        $data = UserMother::regularCreateUserDto();

        $result = $this->service->create($data);

        $this->assertEquals($data->email, $result->email);
        $this->assertDatabaseHas('users', [
            'email' => $data->email,
        ]);
    }

    #[Epic('Integration')]
    public function testLogin(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);
        $data = UserMother::regularLoginUserDto();
        $data->email = $user->email;
        $data->password = 'password123';

        $result = $this->service->login($data);

        $this->assertEquals($user->id, $result->id);
        $this->assertAuthenticatedAs($user);
    }

    #[Epic('Integration')]
    public function testLogout(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $this->service->logout();

        $this->assertGuest();
    }

    #[Epic('Integration')]
    public function testGetSelf(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $result = $this->service->getSelf();

        $this->assertEquals($user->id, $result->id);
    }

}
