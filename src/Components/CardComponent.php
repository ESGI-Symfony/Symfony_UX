<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('card')]
class CardComponent
{

    public function getHousings(): array
    {
        $housings = array(
            array(
                'name' => 'Mars',
                'description' => 'Coucou je suis Mars',
            ),
            array(
                'name' => 'Moon',
                'description' => 'Coucou je suis Moon'
            ),
        );

        return $housings;
    }
}