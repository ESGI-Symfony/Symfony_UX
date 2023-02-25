<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('navbar')]
class NavbarComponent
{
    public array $items = [
        ['name' => 'home', 'path' => '', 'icon' => 'house'],
        ['name' => 'bookings', 'path' => 'front_app_profile_bookings_on_going', 'icon' => 'calendar-event'],
        ['name' => '', 'path' => '', 'icon' => 'search'],
        ['name' => 'housings', 'path' => 'front_app_profile_rentals_index', 'icon' => 'buildings'],
        ['name' => 'account', 'path' => 'front_app_profile_index', 'icon' => 'person'],
    ];
}