<?php

namespace App\Enums;

use Symfony\Contracts\Translation\TranslatorInterface;

trait EnumHelpers
{
    public static function getValues(): array
    {
        $values = array_column(self::cases(), 'value');
        return array_combine($values, $values);
    }

    public static function getValuesTranslated(TranslatorInterface $translator): array
    {
        return array_map(fn($value) => $translator->trans($value), self::getValues());
    }

    public static function getValuesOrderedByTranslation(TranslatorInterface $translator): array
    {
        $values = self::getValuesTranslated($translator);
        uasort($values, fn ($a, $b) => $a > $b);
        return $values;
    }
}