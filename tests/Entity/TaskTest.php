<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends WebTestCase
{
    use FixturesTrait;

    public function getEntity(): Task
    {
        return (new Task())
            ->setTitle("test")
            ->setContent("Content test");
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($task);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidBlankTitleEntity()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testInvalidBlankContentEntity()
    {
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }

    public function testInvalidUsedCode()
    {
        $this->loadFixtureFiles([dirname(__DIR__) . '/fixtures/task.yaml']);
        $this->assertHasErrors($this->getEntity()->setTitle('title de test'), 1);
    }
}
