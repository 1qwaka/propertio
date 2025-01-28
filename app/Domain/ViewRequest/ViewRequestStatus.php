<?php

namespace App\Domain\ViewRequest;

enum ViewRequestStatus: string
{
    case OPEN = 'open';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
