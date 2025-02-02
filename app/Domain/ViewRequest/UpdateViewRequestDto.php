<?php

namespace App\Domain\ViewRequest;

use Carbon\Carbon;

class UpdateViewRequestDto
{
    public function __construct(
        public int $id,
        public ?Carbon $date = null,
        public ?ViewRequestStatus $status = null,
    )
    {
    }
}
