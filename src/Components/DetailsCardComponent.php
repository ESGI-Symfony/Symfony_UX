<?php

namespace App\Components;

use App\Entity\Rental;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('details-card')]
class DetailsCardComponent
{
    public Rental $rental;
    public float|null $rating;
    public array $options;

    /**
     * @return Rental
     */
    public function getRental(): Rental
    {
        return $this->rental;
    }

    public function getRating(): float|null
    {
        return $this->rating;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}