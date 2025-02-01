<?php

namespace Tests\Unit\Controller;

use App\Domain\Agent\CreateAgentDto;
use App\Domain\Agent\IAgentService;
use App\Domain\Agent\UpdateAgentDto;
use App\Http\Controllers\AgentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\Util\AgentMother;
use Tests\TestCase;

class AgentControllerTest extends TestCase
{
    private IAgentService $agentServiceMock;
    private AgentController $agentController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->agentServiceMock = Mockery::mock(IAgentService::class);
        $this->agentController = new AgentController($this->agentServiceMock);
        Validator::shouldReceive('make->fails')->andReturn(false);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testTypes(): void
    {
        $types = ['type1', 'type2'];
        $this->agentServiceMock->shouldReceive('getTypes')->once()->andReturn($types);

        $response = $this->agentController->types(new Request());

        $this->assertEquals(200, $response->status());
        $this->assertEquals(
            ['message' => 'Success', 'items' => $types],
            json_decode($response->getContent(), true)
        );
    }

    public function testRegister(): void
    {
        $createAgentDto = AgentMother::regularCreateAgentDto();
        $agentEntity = AgentMother::regularAgentEntity();

        $this->agentServiceMock->shouldReceive('create')->once()
            ->with(Mockery::on(function ($dto) use ($createAgentDto) {
                return $dto instanceof CreateAgentDto && $dto->typeId === $createAgentDto->typeId;
            }))
            ->andReturn($agentEntity);

        Validator::shouldReceive('make->safe->all')->once()->andReturn((array) $createAgentDto);

        $request = new Request(request: (array) $createAgentDto);
        $response = $this->agentController->register($request);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(
            ['message' => 'Success', 'agent' => (array) $agentEntity],
            json_decode($response->getContent(), true)
        );
    }

    public function testUpdate(): void
    {
        $updateAgentDto = AgentMother::regularUpdateAgentDto();
        $agentEntity = AgentMother::regularAgentEntity();

        $this->agentServiceMock->shouldReceive('update')->once()
            ->with(Mockery::on(function ($dto) use ($updateAgentDto) {
                return $dto instanceof UpdateAgentDto && $dto->id === $updateAgentDto->id;
            }))
            ->andReturn($agentEntity);

        Validator::shouldReceive('make->safe->all')->once()->andReturn((array) $updateAgentDto);

        $request = new Request((array) $updateAgentDto);
        $response = $this->agentController->update($request);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(
            ['message' => 'Success', 'item' => (array) $agentEntity],
            json_decode($response->getContent(), true)
        );
    }

    public function testSelf(): void
    {
        $agentEntity = AgentMother::regularAgentEntity();
        $this->agentServiceMock->shouldReceive('getSelf')->once()->andReturn($agentEntity);

        $response = $this->agentController->self(new Request());

        $this->assertEquals(200, $response->status());
        $this->assertEquals(
            ['message' => 'Success', 'item' => (array) $agentEntity],
            json_decode($response->getContent(), true)
        );
    }
}
