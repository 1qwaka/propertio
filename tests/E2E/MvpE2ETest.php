<?php

namespace Tests\E2E;

use App\Domain\User\UserEntity;
use App\Domain\ViewRequest\ViewRequestStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\Epic;
use Tests\TestCase;
use Throwable;

class MvpE2ETest extends TestCase
{
    private string $baseUrl;
    private string $domain;
    private array $agentCookie = [];
    private array $customerCookie = [];

    private string $uniqueSuffix;

    private array $agentUser, $agent, $property, $advertisement;
    private array $customerUser, $viewRequest;

    protected function setUp(): void
    {
        if (env('CI_SKIP') == 'true') {
            Allure::description("Test Skipped due to CI pipeline flag CI_SKIP=true");
            $this->fail("Skipped due to CI pipeline flag CI_SKIP=true");
        }

        parent::setUp();
        $this->baseUrl = env('APP_E2E_TEST_URL', 'http://localhost:8000');
        $this->domain = Str::between($this->baseUrl, '//', ':');
        $this->uniqueSuffix = time() . rand(1000, 9999);
    }


    private function extractCookies(Response $resp): array
    {
        return collect($resp->cookies->toArray())
            ->keyBy('Name')->map->Value->toArray();
    }

    /**
     * @throws Throwable
     */
    #[Epic('E2E')]
    public function testMvp(): void
    {
        Allure::description(
            "Running with unique suffix: $this->uniqueSuffix; " .
            "with base url: $this->baseUrl;"
        );
        Allure::runStep([$this, 'createAgentUser']);
        Allure::runStep([$this, 'checkSelfAgent']);
        Allure::runStep([$this, 'checkSelfAgent']);
        Allure::runStep([$this, 'createCustomerUser']);
        Allure::runStep([$this, 'checkSelfCustomer']);
        Allure::runStep([$this, 'checkSelfCustomer']);
        Allure::runStep([$this, 'agentRegisterAgent']);
        Allure::runStep([$this, 'checkSelfAgentAgent']);
        Allure::runStep([$this, 'agentCreateProperty']);
        Allure::runStep([$this, 'agentCreateAdvertisement']);
        Allure::runStep([$this, 'agentCreateViewRequest']);
        Allure::runStep([$this, 'agentAcceptViewRequest']);
    }

    public function createAgentUser(): void
    {
        $email = "agent_{$this->uniqueSuffix}@example.com";
        $resp = Http::post("$this->baseUrl/register", [
            'name' => 'Test Agent',
            'email' => $email,
            'password' => 'password',
        ]);
        $this->assertTrue($resp->successful(), $resp->body());
        $this->agentCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->agentCookie);
        $this->assertArrayHasKey('user', $resp->json());
        $this->agentUser = $resp->json()['user'];
    }

    public function agentRegisterAgent(): void
    {
        $resp = Http::withCookies($this->agentCookie, $this->domain)
            ->post("$this->baseUrl/agents/register", [
                'typeId' => 1,
                'name' => 'Oleg',
                'address' => 'Moscow',
                'email' => $this->agentUser['email'],
            ]);

        $this->assertTrue($resp->successful(), $resp->body());
        $this->agentCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->agentCookie);

        $this->assertArrayHasKey('agent', $resp->json());
        $this->agent = $resp->json()['agent'];
        $this->assertNotEmpty($this->agent);
        $this->assertEquals($this->agentUser['email'], $this->agent['email']);
    }

    public function createCustomerUser(): void
    {
        $email = "customer_{$this->uniqueSuffix}@example.com";
        $resp = Http::post("$this->baseUrl/register", [
            'name' => 'Test Customer',
            'email' => $email,
            'password' => 'password',
        ]);
        $this->assertTrue($resp->successful(), $resp->body());
        $this->customerCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->customerCookie);
        $this->assertArrayHasKey('user', $resp->json());
        $this->customerUser = $resp->json()['user'];
    }

    public function checkSelfAgent(): void
    {
        $resp = Http::withCookies($this->agentCookie, $this->domain)
            ->get("$this->baseUrl/self");
        $this->assertTrue($resp->successful(), $resp->body());
        $this->agentCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->agentCookie);
        $this->assertArrayHasKey('item', $resp->json());
        $this->assertEquals($this->agentUser, $resp->json()['item']);
    }

    public function checkSelfAgentAgent(): void
    {
        $resp = Http::withCookies($this->agentCookie, $this->domain)
            ->get("$this->baseUrl/agents/self");

        $this->assertTrue($resp->successful(), $resp->body());
        $this->agentCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->agentCookie);

        $this->assertArrayHasKey('item', $resp->json());
        $this->assertEquals($this->agent, $resp->json()['item']);
    }


    public function checkSelfCustomer(): void
    {
        $resp = Http::withCookies($this->customerCookie, $this->domain)
            ->get("$this->baseUrl/self");

        $this->assertTrue($resp->successful(), $resp->body());
        $this->customerCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->customerCookie);

        $this->assertArrayHasKey('item', $resp->json());
        $this->assertEquals($this->customerUser, $resp->json()['item']);
    }

    public function agentCreateProperty(): void
    {
        $resp = Http::withCookies($this->agentCookie, $this->domain)
            ->post("$this->baseUrl/properties", [
                'name' => 'Property test ' . $this->uniqueSuffix,
                'address' => '123 Main St',
                'price' => rand(1000, 2000),
                'floor' => 3,
                'floorTypeId' => 1,
                'livingSpaceType' => 'secondary',
                'buildingId' => 1,
            ]);

        $this->assertTrue($resp->successful(), $resp->body());
        $this->agentCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->agentCookie);

        $this->assertArrayHasKey('item', $resp->json());
        $this->property = $resp->json()['item'];
        $this->assertNotEmpty($this->property);
    }

    public function agentCreateAdvertisement(): void
    {
        $resp = Http::withCookies($this->agentCookie, $this->domain)
            ->post("$this->baseUrl/advertisements", [
                'propertyId' => $this->property['id'],
                'type' => 'sell',
                'description' => 'Хата огонь test ' . $this->uniqueSuffix,
                'price' => rand(2000, 5000),
            ]);
        $this->assertTrue($resp->successful(), $resp->body());
        $this->agentCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->agentCookie);

        $this->assertArrayHasKey('item', $resp->json());
        $this->advertisement = $resp->json()['item'];
        $this->assertNotEmpty($this->advertisement);
    }

    public function agentCreateViewRequest(): void
    {
        $resp = Http::withCookies($this->customerCookie, $this->domain)
            ->post("$this->baseUrl/views", [
                'propertyId' => $this->property['id'],
                'date' => now()->addDays(2)->toISOString(),
            ]);

        $this->assertTrue($resp->successful(), $resp->body());
        $this->customerCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->customerCookie);

        $this->assertArrayHasKey('item', $resp->json());
        $this->viewRequest = $resp->json()['item'];
        $this->assertNotEmpty($this->viewRequest);
    }

    public function agentAcceptViewRequest(): void
    {
        $resp = Http::withCookies($this->agentCookie, $this->domain)
            ->post("$this->baseUrl/views/{$this->viewRequest['id']}/status", [
                'status' => 'accepted',
            ]);

        $this->assertTrue($resp->successful(), $resp->body());
        $this->agentCookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->agentCookie);

        $this->assertArrayHasKey('item', $resp->json());
        $this->assertEquals($resp->json('item')['status'], ViewRequestStatus::ACCEPTED->value);
    }
}
