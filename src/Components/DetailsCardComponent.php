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
    public bool $showTotalBookings = false;

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

    public function setRating($rating): self
    {
        $this->rating = $rating ? round($rating*2)/2 : 0;
        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}