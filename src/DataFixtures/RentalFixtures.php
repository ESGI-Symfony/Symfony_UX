<?php

namespace App\DataFixtures;

use App\Entity\Rental;
use App\Entity\RentalOption;
use App\Entity\Transport;
use App\Entity\User;
use App\Enums\RentalTypes;
use App\FakeApi\CelestialObjects;
use App\FakeApi\Systems;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RentalFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TransportFixtures::class,
            RentalOptionFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $transports = $manager->getRepository(Transport::class)->findAll();
        $options = $manager->getRepository(RentalOption::class)->findAll();
        // quickly filter lessors
        $users = array_filter($manager->getRepository(User::class)->findAll(), fn(User $user) => $user->isLessor());

        for ($i = 0; $i < 100; $i++) {
            $dateBegin = $faker->dateTimeBetween('now', '+1 year');
            $dateEnd = $faker->dateTimeBetween($dateBegin, '+1 year');
            $object = (new Rental())
                ->setDescription($faker->paragraph)
                ->setMaxCapacity($faker->numberBetween(1, 10))
                ->setRoomCount($faker->numberBetween(1, 10))
                ->setBathroomCount($faker->numberBetween(1, 4))
                ->setPrice($faker->numberBetween(1, 10000))
                ->setRentType($faker->randomElement(RentalTypes::cases()))

                ->setSystem($faker->randomElement(Systems::getValues()))
                ->setLongitude($faker->longitude)
                ->setLatitude($faker->latitude)
                ->setCelestialObject($faker->randomElement(CelestialObjects::getValues()))

                ->setOwner($faker->randomElement($users))
                ->addOption($faker->randomElement($options))
                ->addTransport($faker->randomElement($transports))
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}
