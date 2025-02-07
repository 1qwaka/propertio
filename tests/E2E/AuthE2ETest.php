<?php

namespace Tests\E2E;

use App\Domain\ViewRequest\ViewRequestStatus;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Qameta\Allure\Allure;
use Qameta\Allure\Attribute\Epic;
use Throwable;

class AuthE2ETest extends \Tests\TestCase
{
    private string $baseUrl, $logPath;
    private string $domain;
    private array $cookie = [];
    private array $user = [];
    private string $uniqueSuffix;

    protected function setUp(): void
    {
        parent::setUp();

        if (env('CI_SKIP') == 'true') {
            Allure::description("Test Skipped due to CI pipeline flag CI_SKIP=true");
            $this->fail("Skipped due to CI pipeline flag CI_SKIP=true");
        }

        $this->baseUrl = env('APP_E2E_TEST_URL', 'http://localhost:8000');
        $this->logPath = env('APP_E2E_TEST_LOG_PATH', './storage/logs/laravel.log');
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
    public function test2FA(): void
    {
        Allure::description(
            "Running with unique suffix: $this->uniqueSuffix; " .
            "with base url: $this->baseUrl;"
        );
        Allure::runStep([$this, 'createUser']);
        Allure::runStep([$this, 'logout']);
        Allure::runStep([$this, 'loginWithCredentials']);
        Allure::runStep([$this, 'confirmCode']);
        Allure::runStep([$this, 'checkSelf']);
    }

    public function createUser(): void
    {
        $email = "auth_{$this->uniqueSuffix}@example.com";
        $resp = Http::post("$this->baseUrl/register", [
            'name' => 'Test Auth 2FA',
            'email' => $email,
            'password' => 'password',
        ]);
        $this->assertTrue($resp->successful(), $resp->body());
        $this->cookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->cookie);
    }

    public function logout(): void
    {
        $resp = Http::withCookies($this->cookie, $this->domain)
            ->post("$this->baseUrl/logout");
        $this->assertTrue($resp->successful(), $resp->body());
        $this->assertArrayNotHasKey('user', $resp->json());
        $this->cookie = [];
    }


    public function loginWithCredentials(): void
    {
        $email = "auth_{$this->uniqueSuffix}@example.com";
        $resp = Http::post("$this->baseUrl/login2", [
            'email' => $email,
            'password' => 'password',
        ]);
        $this->assertTrue($resp->successful(), $resp->body());
        $this->cookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->cookie);
        $this->assertArrayNotHasKey('user', $resp->json());
    }


    public function confirmCode(): void
    {
        $code = $this->extractCode();
        $email = "auth_{$this->uniqueSuffix}@example.com";
        $resp = Http::post("$this->baseUrl/confirm", [
            'email' => $email,
            'password' => 'password',
            'code' => $code,
        ]);
        $this->assertTrue($resp->successful(), $resp->body());
        $this->cookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->cookie);
        $this->assertArrayHasKey('user', $resp->json());
        $this->user = $resp->json()['user'];
    }

    public function checkSelf(): void
    {
        $resp = Http::withCookies($this->cookie, $this->domain)
            ->get("$this->baseUrl/self");
        $this->assertTrue($resp->successful(), $resp->body());
        $this->cookie = $this->extractCookies($resp);
        $this->assertNotEmpty($this->cookie);
        $this->assertArrayHasKey('item', $resp->json());
        $this->assertEquals($this->user, $resp->json()['item']);
    }

    private function extractCode(): string
    {
        $text = file_get_contents($this->logPath);
        $code = Str::before(Str::afterLast($text, '<strong>'), '</strong>');
        return $code;
    }

}
