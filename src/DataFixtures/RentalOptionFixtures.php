<?php

namespace App\DataFixtures;

use App\Entity\RentalOption;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RentalOptionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $options = [
            'Air conditioning',
            'Heating',
            'TV',
            'Internet',
            'Kitchen',
            'Washing machine',
            'Dishwasher',
            'Microwave',
            'Fridge',
            'Oven',
            'Toaster',
            'Coffee machine',
            'Hair dryer',
        ];

        foreach ($options as $option) {
            $object = (new RentalOption())
                ->setName($option)
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}
