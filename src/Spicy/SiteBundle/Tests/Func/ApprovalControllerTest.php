<?php

namespace Spicy\SiteBundle\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class ApprovalControllerTest extends WebTestCase
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
     * @group get
     * @group approval
     */
    public function testGetSuccessful($url)
    {
        $this->client->request('GET', $url);

        $response = $this->client->getResponse();

        if($response->getStatusCode() != Response::HTTP_OK)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertTrue($response->isSuccessful());
    }

    public function provideUrlsGet()
    {
        $idArtiste = 22;
        $idVideo = 101;
        $idGenre = $idType = $idHashtag = $idLyrics = $idITW = 2;
        $idApproval = 1;

        return [
            
            //['/admin/hashtag/create_modal'], referrer
            //['/admin/hashtag/create_modal_update/' . $idHashtag], inutile
            
            //['/admin/collaborateur/create_modal'], referrer

            //['/approval'],                                
            ["/approval/$idApproval/show"],                     
            ['/approval/new'],                              
            ['/approval/create'],                          
            ["/approval/$idApproval/edit"],                        
            ["/approval/$idApproval/update"],                    
            ["/approval/$idApproval/approval"],                
            //['/approval/test'],
            
            ['/admin/approval/create_admin'],  
            ['/admin/approval/add_admin_video_youtube'], 
        ];
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
     * @dataProvider provideUrlsredirect
     * @group func
     * @group admin
     * @group approvalredirect
     * @group approval
     */
    public function testRedirectSuccessful($url)
    {
        $this->client->request('GET', $url);

        $response = $this->client->getResponse();

        if($response->getStatusCode() != Response::HTTP_FOUND)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    public function provideUrlsredirect()
    {
        $idApproval = 1;

        return [                                 
            ["/approval/$idApproval/delete"],                      
            ["/approval/$idApproval/disapproval"],
            
        ];
    }

    /**
     * @dataProvider provideUrlsPost
     * @group func
     * @group admin
     * @group approvalpost
     * @group approval
     */
    public function testPostSuccessful($url)
    {
        $this->client->request('POST', $url);

        $response = $this->client->getResponse();

        if($response->getStatusCode() != Response::HTTP_FOUND)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    public function provideUrlsPost()
    {
        $idArtiste = 22;
        $idVideo = 2;

        return [ 
            ['/admin/approval/add_admin_video_youtube'], 
        ];
    }

    /**
     * @group func
     * @group admin
     * @group approval
     * @group approvalpostwrong
     */
    public function testPostWrong()
    {
        $this->client->request('POST', "/admin/approval/new_admin", ['youtubeUrl' => "url_2"]);

        $response = $this->client->getResponse();
        
        if($response->getStatusCode() != Response::HTTP_FOUND)
        var_dump("/admin/approval/new_admin", $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /**
     * @dataProvider provideUrlsGetWrong
     * @group func
     * @group admin
     * @group get
     * @group approval
     */
    public function testGetWrong($url)
    {
        $this->client->request('GET', $url);

        $response = $this->client->getResponse();

        if($response->getStatusCode() != Response::HTTP_NOT_FOUND)
        var_dump($url, $response->getStatusCode());

        //var_dump($response->getContent());

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function provideUrlsGetWrong()
    {
        $idApproval = 99;

        return [                               
            ["/approval/$idApproval/show"], 
            ["/approval/$idApproval/edit"], 
        ];
    }
}