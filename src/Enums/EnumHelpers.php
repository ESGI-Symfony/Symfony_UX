<?php

namespace App\Enums;

trait EnumHelpers
{
    public static function getValues(): array
    {
        return array_column(ReportTypes::cases(), 'value');
    }
}