<?php

namespace App\Enums;

enum BookingCardTypeComponent: string
{
    use EnumHelpers;

    case rental = 'rental';
    case reservation = 'reservation';
}