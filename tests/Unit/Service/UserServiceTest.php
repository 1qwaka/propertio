<?php

namespace Tests\Unit\Service;

use App\Domain\User\IUserRepository;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;
use Tests\Util\UserMother;

class UserServiceTest extends TestCase
{
    private IUserRepository $userRepositoryMock;
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем мок для IUserRepository
        $this->userRepositoryMock = Mockery::mock(IUserRepository::class);

        // Создаем экземпляр UserService с моком репозитория
        $this->userService = new UserService($this->userRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetSelf(): void
    {
        $userEntity = UserMother::regularUserEntity();
        Auth::shouldReceive('id')->once()->andReturn($userEntity->id);
        $this->userRepositoryMock
            ->shouldReceive('find')
            ->once()
            ->with($userEntity->id)
            ->andReturn($userEntity);

        $result = $this->userService->getSelf();

        $this->assertSame($userEntity, $result);
    }

    public function testCreate(): void
    {
        $createUserDto = UserMother::regularCreateUserDto();;
        $userEntity = UserMother::regularUserEntity();
        $this->userRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($createUserDto)
            ->andReturn($userEntity);

        $result = $this->userService->create($createUserDto);

        $this->assertSame($userEntity, $result);
    }

    public function testLogin(): void
    {
        $loginUserDto = UserMother::regularLoginUserDto();
        $userEntity = UserMother::regularUserEntity();
        $this->userRepositoryMock
            ->shouldReceive('login')
            ->once()
            ->with($loginUserDto)
            ->andReturn($userEntity);

        $result = $this->userService->login($loginUserDto);

        $this->assertSame($userEntity, $result);
    }

    public function testLogout(): void
    {
        $this->userRepositoryMock
            ->shouldReceive('logout')
            ->once();

        $this->userService->logout();

        $this->assertTrue(true); // Просто проверяем, что код выполнился без ошибок
    }
}
