<?php

namespace Spicy\ITWBundle\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class ITWControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = self::createClient();
        $this->logIn();
    }

    public function provideUrls()
    {
        $idITW = 1;

        return [            
            ['/admin/itw/'],
            ['/admin/itw/list/' . $idITW],
            ['/admin/itw/' . $idITW],
            ['/admin/itw/new/' . $idITW],
            ['/admin/itw/' . $idITW . '/edit'],            
        ];
    }
    
    /**
     * @dataProvider provideUrls
     * @group func
     * @group itw
     * @group itwget
     */
    public function testPageIsSuccessful($url)
    {
        $this->client->request('GET', $url); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_OK)
        var_dump($url, $response->getStatusCode());
        //var_dump($response->getContent());

        $this->assertTrue($response->isSuccessful());
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
     * @group func
     * @group itw
     * @group itwpost
     */
    public function testPagePostSimple()
    {        
        // $client = $this->client = self::createClient();
        // $this->logIn();
        $idITW = 1;
        $url = '/admin/itw/' . $idITW;
        $this->client->request('POST', $url); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_FOUND)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /**
     * @group func
     * @group itw
     * @group itwput
     */
    public function testPagePut()
    {        
        // $client = $this->client = self::createClient();
        // $this->logIn();
        $idITW = 1;
        $url = '/admin/itw/' . $idITW;
        $this->client->request('PUT', $url); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_OK)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @group func
     * @group itw
     * @group itwdel
     */
    public function testPageDel()
    {        
        // $client = $this->client = self::createClient();
        // $this->logIn();
        $idITW = 1;
        $url = '/admin/itw/' . $idITW;
        $this->client->request('DELETE', $url); 

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_FOUND)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /**
     * @group func
     * @group itw
     * @group itwpost2
     */
//     public function testPagePost()
//     {        
//         $this->logIn();
//         $idITW = 1;
//         $url = '/admin/itw/' . $idITW;

//         $this->client->request('GET', $url); 

//         $extract = $this->crawler->filter('input[name="element_add[_token]"]')
//   ->extract(array('value'));
// $csrf_token = $extract[0];

//         $parameters = [
//             'active' => true,
//             'title' => 'test',
//             'questions' => [
//                 'answer' => 'ok', 
//                 'content' => 'what?', 
//                 'position' => '1', 
//                 'main' => true
//             ],
//             'submit' => 'submit',
//             'token' => $csrf_token
//         ];
        
//         var_dump(json_encode($parameters));
//         $this->client->request('POST', $url, [], [],[], json_encode($parameters)); 

//         $response = $this->client->getResponse();
        
//         if($response->getStatusCode() != Response::HTTP_FOUND)
//         var_dump($url, $response->getStatusCode());

//         //var_dump($response->getContent());

//         // $this->assertContains(
//         //     'Hello World',
//         //     $client->getResponse()->getContent()
//         // );

//         $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
//     }

    /**
     * @group func
     * @group itw
     * @group itwgetw
     */
    public function testWrongShow()
    {        
        //$this->logIn();
        $this->client->request('GET', '/admin/itw/99'); 

        $response = $this->client->getResponse();
        
        // if($response->getStatusCode() != Response::HTTP_NOT_FOUND)
        // var_dump('/admin/itw/99', $response->getStatusCode());
        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @group func
     * @group itw
     * @group itwgetw
     */
    public function testWrongEdit()
    {        
        //$this->logIn();
        $this->client->request('GET', '/admin/99/edit'); 

        $response = $this->client->getResponse();
        
        // if($response->getStatusCode() != Response::HTTP_NOT_FOUND)
        // var_dump('/admin/itw/99', $response->getStatusCode());
        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}