<?php

namespace Spicy\LyricsBundle\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class LyricsControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = self::createClient();
        $this->logIn();
    }

    /**
     * @dataProvider provideUrlsGet
     * @group func
     * @group admin
     * @group lyrics
     */
    public function testPageIsSuccessful($url)
    {
        $this->client->request('GET', $url); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_OK)
        var_dump($url, $response->getStatusCode());

        // var_dump($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function provideUrlsGet()
    {
        return [
            ["/paroles/1/test"],
            ["/admin/paroles/update/1"]
        ];
    }

    /**
     * @group func
     * @group admin
     * @group lyrics
     * @group lyricspost
     */
    public function testPost()
    {
        $this->client->request('POST', "/admin/paroles/update/1"); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_OK)
        var_dump("/admin/paroles/update/1", $response->getStatusCode());

        // var_dump($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @group func
     * @group admin
     * @group lyrics
     * @group lyricspost
     */
    public function testPostWrong()
    {
        $this->client->request('POST', "/admin/paroles/update/21"); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_NOT_FOUND)
        var_dump("/admin/paroles/update/21", $response->getStatusCode());

        // var_dump($response->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
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