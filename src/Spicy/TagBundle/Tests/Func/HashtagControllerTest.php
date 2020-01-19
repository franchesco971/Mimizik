<?php

namespace Spicy\TagBundle\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class HashtagControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = self::createClient();
        $this->logIn();
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

    /**
     * @dataProvider provideUrls
     * @group func
     * @group hashtag
     * @group hashtagget
     */
    public function testPageIsSuccessful($url)
    {
        $this->client->request('GET', $url); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_OK)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function provideUrls()
    {
        $idGenre = $idType = $idHashtag = $idLyrics = 2;

        return [           
            ['/admin/hashtag/new_modal_update/' . $idHashtag],   
            ["/admin/hashtags"],   
        ];
    }

    /**
     * @dataProvider provideUrlsGetWrong
     * @group func
     * @group hashtag
     * @group hashtagget
     */
    public function testGetWrong($url)
    {
        $this->client->request('GET', $url); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_NOT_FOUND)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function provideUrlsGetWrong()
    {
        $idGenre = $idType = $idHashtag = $idLyrics = 2;

        return [
            ["/admin/hashtags/hashtag_$idHashtag/__id__"],
            ["/admin/hashtags/hashtag_$idHashtag/1"],
        ];
    }
    
    /**
     * @dataProvider provideUrlsRedirect
     * @group func
     * @group hashtag
     * @group hashtagre
     */
    public function testRedirectSuccessful($url)
    {        
        $client = $this->client = self::createClient();
        $this->logIn();
        $client->request('GET', $url);

        $response = $this->client->getResponse();

        if($response->getStatusCode() != Response::HTTP_FOUND)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    public function provideUrlsRedirect()
    {
        $idGenre = $idType = $idHashtag = $idLyrics = $idITW = 2;
        
        return [                                    
            // ["/hashtags/$idHashtag/delete"], delete
            ['/admin/hashtag/create_modal_update/' . $idHashtag], 
        ];
    }
}