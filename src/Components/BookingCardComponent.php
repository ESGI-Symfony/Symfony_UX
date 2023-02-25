<?php

namespace App\Components;

use App\Entity\Rental;
use App\Entity\Reservation;
use App\Enums\BookingCardTypeComponent;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('booking-card')]
class BookingCardComponent
{
    public Reservation|Rental $subject;
    public BookingCardTypeComponent $type;
    public function mount(Reservation|Rental $subject, string $type): void
    {
        $this->subject = $subject;

        // sadly, there's no twig core enum handling, so we need to convert the string to an enum
        $this->type = BookingCardTypeComponent::from($type);
    }
}