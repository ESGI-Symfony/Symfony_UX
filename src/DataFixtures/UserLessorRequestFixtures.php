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
        $users = array_filter($users, fn(User $user) => !$user->isLessor());
        // get only a random subset of users (-1 to have at least one non-lessor user)
        $users = array_slice($users, 0, $faker->numberBetween(0, count($users) - 1));

        foreach ($users as $user) {
            if($user->isLessor()) {
                continue;
            }

            $object = (new UserLessorRequest())
                ->setMotivation($faker->text($faker->numberBetween(10, 1000)))
                ->setStatus($faker->randomElement(UserLessorRequestStatus::cases()))
                ->setLessor($user)
            ;

            $user->setPhone($faker->phoneNumber)
                ->setLessorNumber($faker->numberBetween(1000, 9999))
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName);

            $manager->persist($object);
        }

        $manager->flush();
    }
}
