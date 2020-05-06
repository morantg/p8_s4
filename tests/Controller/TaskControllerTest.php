<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;
    
    public function testAddNewTask()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client, $users['user_user']);
        
        $crawler = $client->request('GET','/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'test',
            'task[content]' => 'contenu test'
        ]);
        $client->submit($form);

        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');   
    }
    
    
}