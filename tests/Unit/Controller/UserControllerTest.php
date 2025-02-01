<?php
namespace Tests\Unit\Controller;

use App\Domain\User\IUserService;
use App\Domain\User\CreateUserDto;
use App\Domain\User\LoginUserDto;
use App\Http\Controllers\UserController;
use App\Http\Requests\UserRequest;
use App\Http\View\UserView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Util\UserMother;

class UserControllerTest extends TestCase
{
    private MockInterface $userServiceMock;
    private UserController $userController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userServiceMock = Mockery::mock(IUserService::class);
        $this->userController = new UserController($this->userServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testSelf(): void
    {
        $userEntity = UserMother::regularUserEntity();
        $this->userServiceMock->shouldReceive('getSelf')->once()->andReturn($userEntity);

        $response = $this->userController->self(new Request());

        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'message' => 'success',
            'item' => UserView::toArray($userEntity)
        ], json_decode($response->getContent(), true));
    }

    public function testRegister(): void
    {
        $createUserDto = UserMother::regularCreateUserDto();
        $userEntity = UserMother::regularUserEntity();

        $requestMock = Mockery::mock(UserRequest::class);
        $requestMock->shouldReceive('validated')->once()->andReturn([
            'name' => $createUserDto->name,
            'email' => $createUserDto->email,
            'password' => $createUserDto->password
        ]);

        $this->userServiceMock
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(fn($dto) => $dto instanceof CreateUserDto))
            ->andReturn($userEntity);

        $response = $this->userController->register($requestMock);

        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'message' => 'Registration successful',
            'user' => UserView::toArray($userEntity)
        ], json_decode($response->getContent(), true));
    }

    public function testLogin(): void
    {
        $loginUserDto = UserMother::regularLoginUserDto();
        $userEntity = UserMother::regularUserEntity();

        $requestMock = Request::create('/login', 'POST', [
            'email' => $loginUserDto->email,
            'password' => $loginUserDto->password
        ]);

        Validator::shouldReceive('make->fails')->once()->andReturn(false);
        Validator::shouldReceive('make->safe->only')->once()->andReturn([
            'email' => $loginUserDto->email,
            'password' => $loginUserDto->password
        ]);

        $this->userServiceMock->shouldReceive('login')->once()
            ->with(Mockery::on(fn($dto) => $dto instanceof LoginUserDto))
            ->andReturn($userEntity);

        $response = $this->userController->login($requestMock);

        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'message' => 'Login successful',
            'user' => UserView::toArray($userEntity)
        ], json_decode($response->getContent(), true));
    }

    public function testLogout(): void
    {
        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('session->invalidate')->once();
        $requestMock->shouldReceive('session->regenerateToken')->once();

        $this->userServiceMock->shouldReceive('logout')->once();

        $response = $this->userController->logout($requestMock);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(
            ['message' => 'Logout success'],
            json_decode($response->getContent(), true)
        );
    }
}
