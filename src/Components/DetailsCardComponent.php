<?php

namespace App\Components;

use App\Entity\Rental;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('details-card')]
class DetailsCardComponent
{
    public Rental $rental;

    /**
     * @return Rental
     */
    public function getRental(): Rental
    {
        return $this->rental;
    }
}