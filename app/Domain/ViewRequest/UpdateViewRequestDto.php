<?php

namespace App\Domain\ViewRequest;

use Carbon\Carbon;

readonly class UpdateViewRequestDto
{
    public function __construct(
        int $id,
        public ?Carbon $date = null,
        public ?ViewRequestStatus $status = null,
    )
    {
    }
}
