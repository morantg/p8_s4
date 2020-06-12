<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\NeedLogin;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;
    use NeedLogin;
    
    public function setUp()
    {
        $this->fixtures = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->client = static::createClient();
    }

    public function testRedirectToLogin()
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testDisplayLogin()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Se connecter');
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginWithBadCredentials()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'test',
            '_password' => 'fakepassword'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLogin()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'test',
            '_password' => '0000'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('http://localhost/');
    }

    public function testLetAuthencatedUserAccesTasks()
    {
        $this->login($this->client, $this->fixtures['user_user']);
        $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdminRequireAdminRole()
    {
        $this->login($this->client, $this->fixtures['user_user']);
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminRequireAdminRoleWithSufficientRole()
    {
        $this->login($this->client, $this->fixtures['user_admin']);
        $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
