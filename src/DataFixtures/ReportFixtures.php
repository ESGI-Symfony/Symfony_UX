<?php

namespace App\DataFixtures;

use App\Entity\Rental;
use App\Entity\Report;
use App\Entity\User;
use App\Enums\ReportTypes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReportFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            RentalFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $users = $manager->getRepository(User::class)->findAll();
        $rentals = $manager->getRepository(Rental::class)
            ->findBy([], [], 10, $faker->numberBetween(1, 3));

        foreach ($rentals as $rental) {
            $object = (new Report())
                ->setComment($faker->text($faker->numberBetween(10, 1000)))
                ->setType($faker->randomElement(ReportTypes::cases()))
                ->setRental($rental)
                ->setAuthor($faker->randomElement($users))
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}
