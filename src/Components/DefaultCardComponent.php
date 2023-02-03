<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('default-card')]
class DefaultCardComponent
{

    public function getHousings(): array
    {
        $housings = array(
            array(
                'name' => 'Mars',
            ),
            array(
                'name' => 'Moon',
            ),
        );

        return $housings;
    }
}