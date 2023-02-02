<?php

namespace App\FakeApi;

// to replace with a Nasa api call if we have time (this is why there's no assert in the entity)
use App\Enums\EnumHelpers;

enum Systems: string
{
    use EnumHelpers;

    case Solar = 'solar_system';
    case AlphaCentauri = 'alpha_centauri';
    case Andromeda = 'andromeda';
    case MilkyWay = 'milky_way';
}