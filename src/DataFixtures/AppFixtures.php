<?php

namespace App\DataFixtures;


use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        
        $user = new User();
        $user->setUsername('user')
             ->setPassword('$2y$10$GYxcpAZ7rAu3IAhkG86SqON6mSRRd8YDthi1xGqY0TuKwXeVANQ96')
             ->setEmail('user@domain.com');
        $manager->persist($user);
        
        $user2 = new User();
        $user2->setUsername('admin')
             ->setPassword('$2y$10$GYxcpAZ7rAu3IAhkG86SqON6mSRRd8YDthi1xGqY0TuKwXeVANQ96')
             ->setEmail('admin@domain.com')
             ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user2);

        for ($i = 0; $i < 5; $i++) {
            $task = new Task();

            $task->setCreatedAt($faker->dateTimeBetween('-6 months'))
                 ->setTitle($faker->word())
                 ->setContent($faker->sentence(10, true));  
            $manager->persist($task);
        }

        for ($i = 0; $i < 10; $i++) {
            $task2 = new Task();

            $task2->setUser($user)
                  ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                  ->setTitle($faker->word())
                  ->setContent($faker->sentence(10, true));  
            $manager->persist($task2);
        }

        for ($i = 0; $i < 10; $i++) {
            $task3 = new Task();

            $task3->setUser($user2)
                  ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                  ->setTitle($faker->word())
                  ->setContent($faker->sentence(10, true));  
            $manager->persist($task3);
        }

        $manager->flush();
    }
}
