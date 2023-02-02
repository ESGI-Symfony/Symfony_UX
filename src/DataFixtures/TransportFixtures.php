<?php

namespace App\DataFixtures;

use App\Entity\Transport;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TransportFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $transports = [
            'Train',
            'Rocket',
            'Hyperloop',
            'Worm hole',
        ];

        foreach ($transports as $transport) {
            $object = (new Transport())
                ->setName($transport)
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}
