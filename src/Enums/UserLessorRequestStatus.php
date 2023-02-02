<?php

namespace App\Enums;

// to replace with a Nasa api call if we have time (this is why there's no assert in the entity)
enum UserLessorRequestStatus: string
{
    use EnumHelpers;

    case validated = 'validated';
    case pending = 'pending';
    case rejected = 'rejected';
}