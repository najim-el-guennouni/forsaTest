<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Cours;
use App\Entity\Level;
use App\Entity\WashList;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $user = new User();
        $user->setfirstName($faker->firstName)
            ->setLastName($faker->lastName)
            ->setPhone('0708112623')
            ->setEmail('test@test.ma')
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    '123456'
                )
            );

        $manager->persist($user);

        $levelNames = ['Advance', 'Beginner', 'Intermediate'];
        $levels = [];
        
        foreach ($levelNames as $levelName) {
            $level = new Level();
            $level->setName($levelName)
                  ->setDescription($faker->sentence)
                  ->setActive(1);
        
            $manager->persist($level);
            $levels[] = $level;
        }


        $cours = [];
        for ($i = 0; $i < 20; $i++) {
            $cour = new Cours();
            $cour->setTitle($faker->sentence)
                ->setUser($user)
                ->setActive(1)
                ->setDescription($faker->paragraph)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setLevel($faker->randomElement($levels));

            $manager->persist($cour);
            $cours[] = $cour;


            if ($faker->boolean(70)) {
                $washList = new WashList();
                $washList->setUser($user)
                    ->setCours($faker->randomElement($cours));
                $manager->persist($washList);
            }
        }
        $manager->flush();
    }
}
