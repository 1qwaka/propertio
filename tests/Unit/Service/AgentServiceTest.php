<?php

namespace Tests\Unit\Service;

use App\Domain\Agent\CreateAgentDto;
use App\Domain\Agent\IAgentRepository;
use App\Domain\Agent\UpdateAgentDto;
use App\Services\AgentService;
use App\Services\UserService;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Tests\Util\AgentMother;
use Tests\Util\UserMother;

class AgentServiceTest extends TestCase
{
    private MockInterface $agentRepositoryMock;
    private MockInterface $userServiceMock;
    private AgentService $agentService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agentRepositoryMock = Mockery::mock(IAgentRepository::class);
        $this->userServiceMock = Mockery::mock(UserService::class);
        $this->agentService = new AgentService($this->agentRepositoryMock, $this->userServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetSelf(): void
    {
        $userEntity = UserMother::regularUserEntity();
        $agentEntity = AgentMother::regularAgentEntity();

        $this->userServiceMock->shouldReceive('getSelf')->once()->andReturn($userEntity);
        $this->agentRepositoryMock->shouldReceive('findByUser')->once()
            ->with($userEntity->id)->andReturn($agentEntity);

        $result = $this->agentService->getSelf();

        $this->assertSame($agentEntity, $result);
    }

    public function testGetTypes(): void
    {
        $types = ['type1', 'type2', 'type3'];
        $this->agentRepositoryMock->shouldReceive('getTypes')->once()->andReturn($types);

        $result = $this->agentService->getTypes();

        $this->assertEquals($types, $result);
    }

    public function testCreate(): void
    {
        $userEntity = UserMother::regularUserEntity();
        $createAgentDto = AgentMother::regularCreateAgentDto();
        $agentEntity = AgentMother::regularAgentEntity();

        $this->userServiceMock->shouldReceive('getSelf')->once()->andReturn($userEntity);
        $this->agentRepositoryMock->shouldReceive('create')->once()->with(Mockery::on(fn($dto) => $dto instanceof CreateAgentDto && $dto->userId === $userEntity->id))->andReturn($agentEntity);

        $result = $this->agentService->create($createAgentDto);

        $this->assertSame($agentEntity, $result);
    }

    public function testUpdate(): void
    {
        $agentEntity = AgentMother::regularAgentEntity();
        $updateAgentDto = AgentMother::regularUpdateAgentDto();

        $this->agentRepositoryMock->shouldReceive('update')->once()->with(Mockery::on(fn($dto) => $dto instanceof UpdateAgentDto && $dto->id === $agentEntity->id))->andReturn($agentEntity);
        $this->agentService = Mockery::mock(AgentService::class, [$this->agentRepositoryMock, $this->userServiceMock])->makePartial();
        $this->agentService->shouldReceive('getSelf')->once()->andReturn($agentEntity);

        $result = $this->agentService->update($updateAgentDto);

        $this->assertSame($agentEntity, $result);
    }
}
