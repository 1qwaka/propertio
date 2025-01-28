<?php

namespace App\Domain\ViewRequest;

use Carbon\Carbon;

class ViewRequestEntity
{
    public function __construct(
        public ?int $id,
        public ViewRequestStatus $status,
        public ?Carbon $date,
        public int $propertyId,
        public int $userId,
    )
    {
    }
}
