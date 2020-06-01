<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->client = static::createClient();
        $this->login($this->client, $this->fixtures['user_admin']);
    }

    public function testAddNewUser()
    {
        $crawler = $this->client->request('GET', '/');
        $link = $crawler->SelectLink('Créer un utilisateur')->link();
        $crawler = $this->client->click($link);
        
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user2',
            'user[password][first]' => '123',
            'user[password][second]' => '123',
            'user[email]' => 'user2@domain.fr',
            'user[roles][0]' => false
        ]);
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditUser()
    {
        $crawler = $this->client->request('GET', '/users/3/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' => 'new username',
            'user[password][first]' => '123',
            'user[password][second]' => '123',
        ]);
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
