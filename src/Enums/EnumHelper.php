<?php

namespace App\Enums;

trait EnumHelper
{
    public static function getValues(): array
    {
        return array_column(ReportTypes::cases(), 'value');
    }
}