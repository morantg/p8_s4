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

    public function setUp()
    {
        $this->fixtures = $this->loadFixtureFiles([__DIR__ . '/users.yaml', __DIR__ . '/tasks.yaml']);
        $this->client = static::createClient();
        $this->login($this->client, $this->fixtures['user_admin']);
    }
    
    public function testAddNewTask()
    {
        $crawler = $this->client->request('GET', '/');
        $link = $crawler->SelectLink('Créer une nouvelle tâche')->link();
        $crawler = $this->client->click($link);
        
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'test',
            'task[content]' => 'contenu test'
        ]);
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditTask()
    {
        $crawler = $this->client->request('GET', '/tasks/1/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'nouveau title',
            'task[content]' => 'nouveau content'
        ]);
        $this->client->submit($form);

        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditTaskRequireAdminRole()
    {
        $this->login($this->client, $this->fixtures['user_user']);
        $this->client->request('GET', '/tasks/1/edit');
        $this->assertResponseRedirects('/tasks');
    }

    public function testAccesTasksCompleted()
    {
        $this->client->request('GET', '/tasks/completed');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testToggleTask()
    {
        $crawler = $this->client->request('GET', '/tasks/todo');
        $form = $crawler->SelectButton('Marquer comme faite')->form();
        $this->client->submit($form);
        
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteTask()
    {
        $crawler = $this->client->request('GET', '/tasks');
        $form = $crawler->SelectButton('Supprimer')->form();
        $this->client->submit($form);
        
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
