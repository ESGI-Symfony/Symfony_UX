<?php

namespace App\DataFixtures;

use App\Entity\Rental;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            RentalFixtures::class,
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $totalRentals = $manager->getRepository(Rental::class)->count([]);
        $rentals = $manager->getRepository(Rental::class)->findBy([], [], $faker->numberBetween(10, $totalRentals));
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($rentals as $rental) {
            $begin = $faker->dateTimeBetween('-2 years', 'now');
            $end = $faker->dateTimeBetween($begin, 'now');
            $addReview = $faker->boolean();

            for($i = 0; $i < $faker->numberBetween(0, 10); $i++) {
                $object = (new Reservation())
                    ->setRental($rental)
                    ->setPaymentToken($faker->uuid)
                    ->setBuyer($faker->randomElement($users))
                    ->setDateBegin($begin)
                    ->setDateEnd($end)
                    ->setReviewMark($addReview ? $faker->numberBetween(1, 5) : null)
                    ->setReviewComment($addReview ? $faker->paragraph : null)
                    ->setCreatedAt($faker->dateTimeBetween('-3 years', $begin))
                ;
                $manager->persist($object);
            }
        }

        $manager->flush();
    }
}
