<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserLessorRequest;
use App\Enums\UserLessorRequestStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserLessorRequestFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            if($user->isLessor()) {
                continue;
            }

            $object = (new UserLessorRequest())
                ->setMotivation($faker->text($faker->numberBetween(10, 1000)))
                ->setStatus($faker->randomElement(UserLessorRequestStatus::cases()))
                ->setLessor($user)
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}
