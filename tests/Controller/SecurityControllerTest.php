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
    
    public function testRedirectToLogin()
    {
        $client = static::createClient();
        $client->request('GET','/tasks');
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testDisplayLogin()
    {
        $client = static::createClient();
        $client->request('GET','/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Se connecter');
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'test',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();    
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSuccessfullLogin()
    {
        $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $client = static::createClient();
        $crawler = $client->request('GET','/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'test',
            '_password' => '0000'
        ]);
        $client->submit($form);
        /*$csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $client->request('POST', 'login',[
            '_csrf_token' => $csrfToken,
            '_username' => 'test',
            '_password' => '0000'
        ]);*/
        $this->assertResponseRedirects('http://localhost/');
    }

    public function testLetAuthencatedUserAccesTasks()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client, $users['user_user']);
        $client->request('GET','/tasks');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdminRequireAdminRole()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client, $users['user_user']);
        $client->request('GET','/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminRequireAdminRoleWithSufficientRole()
    {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client, $users['user_admin']);
        $client->request('GET','/users');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}