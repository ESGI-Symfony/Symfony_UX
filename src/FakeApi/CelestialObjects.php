<?php

namespace App\FakeApi;

// to replace with a Nasa api call if we have time (this is why there's no assert in the entity)
use App\Enums\EnumHelpers;

enum CelestialObjects: string
{
    use EnumHelpers;

    case Earth = 'earth';
    case Mars = 'mars';
    case Jupiter = 'jupiter';
    case Saturn = 'saturn';
    case Uranus = 'uranus';
    case Neptune = 'neptune';
    case Pluto = 'pluto';
    case Mercury = 'mercury';
    case Venus = 'venus';
    case Moon = 'moon';
    case Io = 'io';
    case Europa = 'europa';
    case Ganymede = 'ganymede';
    case Callisto = 'callisto';
    case Titan = 'titan';
    case Triton = 'triton';
    case Charon = 'charon';
    case Ceres = 'ceres';
    case Eris = 'eris';
    case Makemake = 'makemake';
    case Haumea = 'haumea';
    case Sedna = 'sedna';
    case Quaoar = 'quaoar';
    case Orcus = 'orcus';
    case Salacia = 'salacia';
    case Ixion = 'ixion';
    case Varuna = 'varuna';
    case Nix = 'nix';
    case Hydra = 'hydra';
}