<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends WebTestCase
{
    use FixturesTrait;

    public function getEntity(): User
    {
        return (new User())
            ->setUsername("test")
            ->setPassword("123")
            ->setEmail("test@gmail.com");
    }

    /*public function assertHasErrors(InvitationCode $code, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($code);
        $this->assertCount($number, $error);
    }*/
    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
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

    public function testInvalidBlankUsernameEntity()
    {
        $this->assertHasErrors($this->getEntity()->setUsername(''), 1);
    }

    public function testInvalidBlankPasswordEntity()
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    }

    public function testInvalidBlankEmailEntity()
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testInvalidUsedEmail()
    {
        $this->loadFixtureFiles([dirname(__DIR__) . '/fixtures/user.yaml']);
        $this->assertHasErrors($this->getEntity()->setEmail('test@domain.fr'), 1);
    }

    public function testInvalidEmailFormat()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('1234'), 1);
    }
}
