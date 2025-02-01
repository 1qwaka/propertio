<?php

namespace Tests\Util;

use App\Domain\Agent\AgentEntity;
use App\Domain\Agent\CreateAgentDto;
use App\Domain\Agent\UpdateAgentDto;
use App\Domain\Agent\AgentStatsDto;

class AgentMother
{
    public const DEFAULT_ID = 1;
    public const DEFAULT_TYPE_ID = 2;
    public const DEFAULT_NAME = 'Test Agent';
    public const DEFAULT_ADDRESS = '123 Test Street';
    public const DEFAULT_EMAIL = 'agent@example.com';
    public const DEFAULT_USER_ID = 1;
    public const DEFAULT_COUNT = 10;

    public static function regularAgentEntity(): AgentEntity
    {
        return new AgentEntity(
            self::DEFAULT_ID,
            self::DEFAULT_TYPE_ID,
            self::DEFAULT_NAME,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_EMAIL,
            self::DEFAULT_USER_ID
        );
    }

    public static function regularCreateAgentDto(): CreateAgentDto
    {
        return new CreateAgentDto(
            self::DEFAULT_TYPE_ID,
            self::DEFAULT_NAME,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_EMAIL,
            self::DEFAULT_USER_ID
        );
    }

    public static function regularUpdateAgentDto(): UpdateAgentDto
    {
        return new UpdateAgentDto(
            self::DEFAULT_ID,
            self::DEFAULT_TYPE_ID,
            self::DEFAULT_NAME,
            self::DEFAULT_ADDRESS,
            self::DEFAULT_EMAIL
        );
    }

    public static function regularAgentStatsDto(): AgentStatsDto
    {
        return new AgentStatsDto(self::DEFAULT_COUNT);
    }
}
