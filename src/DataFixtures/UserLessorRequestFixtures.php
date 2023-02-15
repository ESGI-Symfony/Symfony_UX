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
        $userNonLessor = array_filter($users, fn(User $user) => !$user->isLessor());

        if (count($userNonLessor) <= 1) {
            // force at least one lessor
            $lessor = $userNonLessor[0];
            $lessor->setRoles(['ROLE_USER', 'ROLE_LESSOR']);
        } else {
            // get only a random subset of users (-1 to have at least one non-lessor user)
            $userNonLessor = array_slice($userNonLessor, 1, $faker->numberBetween(0, count($userNonLessor) - 1));
        }

        foreach ($userNonLessor as $user) {
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
