<?php

namespace Service;
use App\Domain\Agent\IAgentService;
use App\Domain\Property\IPropertyRepository;
use App\Domain\Property\LivingSpaceType;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use App\Services\PropertyService;
use App\Exceptions\WithErrorCodeException;
use Tests\Util\AgentMother;
use Tests\Util\PropertyMother;

class PropertyServiceTest extends TestCase
{
    private IPropertyRepository $repository;
    private IAgentService $agentService;
    private PropertyService $service;

    protected function setUp(): void
    {
        $this->repository = m::mock(IPropertyRepository::class);
        $this->agentService = m::mock(IAgentService::class);
        $this->service = new PropertyService($this->repository, $this->agentService);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testGetFloorTypes(): void
    {
        $expectedFloorTypes = ['Type1', 'Type2', 'Type3'];

        $this->repository
            ->shouldReceive('getFloorTypes')
            ->once()
            ->andReturn($expectedFloorTypes);

        $result = $this->service->getFloorTypes();

        $this->assertEquals($expectedFloorTypes, $result);
    }

    public function testGetSpaceTypes(): void
    {
        $expectedSpaceTypes = [LivingSpaceType::PRIMARY, LivingSpaceType::SECONDARY];

        $result = $this->service->getSpaceTypes();

        $this->assertEquals($expectedSpaceTypes, $result);
    }

    public function testCreate(): void
    {
        $data = PropertyMother::defaultCreatePropertyDto();
        $expectedEntity = PropertyMother::defaultPropertyEntity();
        $agent = AgentMother::regularAgentEntity();

        $this->agentService
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agent);

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with(m::on(function ($dto) use ($agent) {
                return $dto->agentId === $agent->id;
            }))
            ->andReturn($expectedEntity);

        $result = $this->service->create($data);

        $this->assertEquals($expectedEntity, $result);
    }

    public function testFind(): void
    {
        $id = 1;
        $expectedEntity = PropertyMother::defaultPropertyEntity();

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($expectedEntity);

        $result = $this->service->find($id);

        $this->assertEquals($expectedEntity, $result);
    }

    public function testGet(): void
    {
        $data = PropertyMother::defaultGetPropertiesDto();
        $expectedPageDto = PropertyMother::defaultPropertiesPageDto();

        $this->repository
            ->shouldReceive('get')
            ->once()
            ->with($data)
            ->andReturn($expectedPageDto);

        $result = $this->service->get($data);

        $this->assertEquals($expectedPageDto, $result);
    }

    public function testUpdateSuccess(): void
    {
        $data = PropertyMother::defaultUpdatePropertyDto();
        $expectedEntity = PropertyMother::defaultPropertyEntity();
        $agent = AgentMother::regularAgentEntity();
        $property = PropertyMother::defaultPropertyEntity();
        $property->agentId = $agent->id;

        $this->agentService
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agent);

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with($data->id)
            ->andReturn($property);

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($data)
            ->andReturn($expectedEntity);

        $result = $this->service->update($data);

        $this->assertEquals($expectedEntity, $result);
    }

    public function testUpdateAccessDenied(): void
    {
        $data = PropertyMother::defaultUpdatePropertyDto();
        $agent = AgentMother::regularAgentEntity();
        $property = PropertyMother::defaultPropertyEntity();
        $property->agentId = $agent->id + 1;

        $this->agentService
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agent);

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with($data->id)
            ->andReturn($property);

        $this->expectException(WithErrorCodeException::class);
        $this->expectExceptionMessage('You don\'t have access to edit this property');
        $this->expectExceptionCode(403);

        $this->service->update($data);
    }

    public function testDeleteSuccess(): void
    {
        $id = 1;
        $agent = AgentMother::regularAgentEntity();
        $property = PropertyMother::defaultPropertyEntity();
        $property->agentId = $agent->id;


        $this->agentService
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agent);

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($property);

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with($id);

        $this->service->delete($id);

        $this->assertTrue(true);
    }

    public function testDeleteAccessDenied(): void
    {
        $id = 1;
        $agent = AgentMother::regularAgentEntity();
        $property = PropertyMother::defaultPropertyEntity();
        $property->agentId = $agent->id + 1;

        $this->agentService
            ->shouldReceive('getSelf')
            ->once()
            ->andReturn($agent);

        $this->repository
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($property);

        $this->expectException(WithErrorCodeException::class);
        $this->expectExceptionMessage('You don\'t have access to edit this property');
        $this->expectExceptionCode(403);

        $this->service->delete($id);
    }
}
