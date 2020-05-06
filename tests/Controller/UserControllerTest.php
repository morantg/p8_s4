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
    
    public function testAddNewUser()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client, $users['user_admin']);
        
        $crawler = $client->request('GET','/');
        $link = $crawler->SelectLink('Créer un utilisateur')->link();
        $crawler = $client->click($link);
        
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user',
            'user[password][first]' => '123',
            'user[password][second]' => '123',
            'user[email]' => 'user@domain.fr',
            'user[roles][0]' => false
        ]);
        $client->submit($form);

        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');   
    }
    
    
}