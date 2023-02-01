<?php

namespace App\Enums;

enum ReportTypes: string
{
    use EnumHelper;
    // abusive behavior of the owner, unavailable, etc.
    case Owner = 'owner_behavior';

    // problem with the rental, inside the location, the equipment, etc.
    case Rental = 'rental';

    // problem with the payment, extra payment, etc.
    case Payment = 'payment';

    // problem with the description of the rental, publicity, etc.
    case Description = 'description';

    // problem with the place, the environment, etc.
    case Place = 'place';
    
    // others
    case Other = 'other';
}