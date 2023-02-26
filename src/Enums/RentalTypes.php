<?php

namespace App\Enums;

enum RentalTypes: string
{
    use EnumHelpers;

    case House = 'house';
    case Flat = 'flat';
    case Cottage = 'cottage';
    case SpaceStation = 'space_station';
    case Villa = 'villa';
    case Satellite = 'satellite';
}