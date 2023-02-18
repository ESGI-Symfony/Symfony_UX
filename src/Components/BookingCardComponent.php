<?php

namespace App\Components;

use App\Entity\Rental;
use App\Enums\BookingCardTypeComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('booking-card')]
class BookingCardComponent
{
    public Rental $rental;
    public BookingCardTypeComponent $type;
    public function mount(Rental $rental, string $type): void
    {
        $this->rental = $rental;

        // sadly, there's no twig core enum handling, so we need to convert the string to an enum
        $this->type = BookingCardTypeComponent::from($type);
    }
}