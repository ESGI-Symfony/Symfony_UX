<?php

namespace App\Enums;

trait EnumHelpers
{
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}