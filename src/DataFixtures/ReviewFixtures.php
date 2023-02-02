<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ReservationFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $users = $manager->getRepository(User::class)->findAll();
        $reservations = $manager->getRepository(Reservation::class)->findBy([], [], $faker->numberBetween(1, 6));

        foreach ($reservations as $reservation) {
            $object = (new Review())
                ->setComment($faker->text($faker->numberBetween(10, 1000)))
                ->setRating($faker->numberBetween(1, 5))
                ->setReservation($reservation)
                ->setAuthor($faker->randomElement($users))
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}
