<?php

namespace App\Tests\Repository;

use App\Repository\TaskRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskRepositoryTest extends WebTestCase
{

    use FixturesTrait;
    
    public function testCount() {
        self::bootKernel();
        $users = $this->loadFixtureFiles([
            __DIR__ . '/TaskRepositoryTestFixtures.yaml'
        ]);
        $users = self::$container->get(TaskRepository::class)->count([]);
        $this->assertEquals(10, $users);
    }
}