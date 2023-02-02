<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('navbar')]
class NavbarComponent
{
    public string $item1;
    public string $item2;
    public string $item3;
    public string $item4;
}