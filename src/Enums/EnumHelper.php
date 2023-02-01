<?php

namespace App\Enums;

trait EnumHelperTrait
{
    public static function getValues(): array
    {
        return array_column(ReportTypes::cases(), 'value');
    }
}