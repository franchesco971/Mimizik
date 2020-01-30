<?php

namespace Spicy\AppBundle\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class FacebookControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = self::createClient();
        $this->logIn();
    }

    public function provideUrls()
    {
        return [            
           // ['/apps/facebook/token'],
            ['/apps/facebook/login'],
            ['/apps/facebook/test']           
        ];
    }
    
    /**
     * @dataProvider provideUrls
     * @group func
     * @group app
     */
    public function testPageIsSuccessful($url)
    {
        $this->client->request('GET', $url); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_FOUND)
        var_dump($url, $response->getStatusCode());
        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /**
     * @group func
     * @group app
     */
    public function testNotFound()
    {
        $this->client->request('GET', '/apps/facebook/token'); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_NOT_FOUND)
        var_dump('/apps/facebook/token', $response->getStatusCode());
        // var_dump($response->getContent());

        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $userManager = static::$kernel->getContainer()->get('fos_user.user_manager');
        
        $user = $userManager->findUserByUsername('user_1');
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

   
}