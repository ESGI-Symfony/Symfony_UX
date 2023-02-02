<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $isLessor = $faker->boolean();
            $roles = ['ROLE_USER'];
            if($isLessor) {
                $roles[] = 'ROLE_LESSOR';
            }

            $object = (new User())
                ->setNickname($faker->name)
                ->setEmail($faker->email)
                ->setPassword('$2y$13$a9kys5sCWtO1AuGGOnV3Ius7o5sJ96OjPn9Wru2C7NEQrKRwMFwHm') // pwd
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setIsVerified(true)
                ->setRoles($roles)
                ->setLessorNumber($isLessor ? $faker->numberBetween(1000, 9999) : null)
                ->setUuid($faker->uuid)
            ;

            $manager->persist($object);
        }

        $manager->flush();
    }
}
