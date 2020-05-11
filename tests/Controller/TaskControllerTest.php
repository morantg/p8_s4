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
        
        $crawler = $client->request('GET','/');
        $link = $crawler->SelectLink('Créer une nouvelle tâche')->link();
        $crawler = $client->click($link);
        
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'test',
            'task[content]' => 'contenu test'
        ]);
        $client->submit($form);

        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');   
    }

    public function testEditTask()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml', __DIR__ . '/tasks.yaml']);
        $this->login($client, $users['user_admin']);
        
        $crawler = $client->request('GET','/tasks/1/edit');
        
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'nouveau title',
            'task[content]' => 'nouveau content'
        ]);
        $client->submit($form);

        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');   
    }

    public function testToggleTask()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml', __DIR__ . '/tasks.yaml']);
        $this->login($client, $users['user_admin']);
        
        $crawler = $client->request('GET','/tasks/todo');
        
        $form = $crawler->SelectButton('Marquer comme faite')->form();
        $client->submit($form);
        
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteTask()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml', __DIR__ . '/tasks.yaml']);
        $this->login($client, $users['user_admin']);
        
        $crawler = $client->request('GET','/tasks');
        
        $form = $crawler->SelectButton('Supprimer')->form();
        $client->submit($form);
        
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

}