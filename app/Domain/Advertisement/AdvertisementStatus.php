<?php

namespace App\Domain\Advertisement;


enum AdvertisementStatus: string
{
    case SELL = 'sell';
    case RENT = 'rent';
}
