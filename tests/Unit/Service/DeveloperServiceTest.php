<?php

namespace Test\Unit\Service;

use App\Domain\Developer\IDeveloperRepository;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use App\Services\DeveloperService;
use Tests\Util\DeveloperMother;

class DeveloperServiceTest extends TestCase
{
    private IDeveloperRepository $repository;
    private DeveloperService $service;

    protected function setUp(): void
    {
        $this->repository = m::mock(IDeveloperRepository::class);
        $this->service = new DeveloperService($this->repository);
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testCreate(): void
    {
        $data = DeveloperMother::defaultCreateDeveloperDto();
        $expectedEntity = DeveloperMother::defaultDeveloperEntity();

        $this->repository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($expectedEntity);

        $result = $this->service->create($data);

        $this->assertEquals($expectedEntity, $result);
    }

    public function testFind(): void
    {
        $id = 1;
        $expectedEntity = DeveloperMother::defaultDeveloperEntity();

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
        $data = DeveloperMother::defaultGetDevelopersDto();
        $expectedPageDto = DeveloperMother::defaultDeveloperPageDto();

        $this->repository
            ->shouldReceive('paginate')
            ->once()
            ->with($data)
            ->andReturn($expectedPageDto);

        $result = $this->service->get($data);

        $this->assertEquals($expectedPageDto, $result);
    }

    public function testUpdate(): void
    {
        $data = DeveloperMother::defaultUpdateDeveloperDto();
        $expectedEntity = DeveloperMother::defaultDeveloperEntity();

        $this->repository
            ->shouldReceive('update')
            ->once()
            ->with($data)
            ->andReturn($expectedEntity);

        $result = $this->service->update($data);

        $this->assertEquals($expectedEntity, $result);
    }

    public function testDelete(): void
    {
        $id = 1;

        $this->repository
            ->shouldReceive('delete')
            ->once()
            ->with($id);

        $this->service->delete($id);

        // Проверяем, что метод был вызван
        $this->assertTrue(true);
    }
}
