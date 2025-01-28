<?php

namespace App\Domain\Agent;

enum AgentType: string
{
    case PHYSICAL_PERSON = 'physical';
    case LEGAL_PERSON = 'legal';
    case REALTOR = 'realtor';
    case DEVELOPER = 'developer';
    case AGENCY = 'agency';
    case OWNER = 'owner';
}

