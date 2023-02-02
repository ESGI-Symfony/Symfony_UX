<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('booking-card')]
class BookingCardComponent
{

    public function getHousings(): array
    {
        $housings = array(
            array(
                'name' => 'Mars',
                'rating' => 3
            ),
            array(
                'name' => 'Moon',
                'rating' => null
            ),
        );

        return $housings;
    }
}