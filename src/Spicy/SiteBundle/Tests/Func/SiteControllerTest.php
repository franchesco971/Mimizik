<?php

namespace Spicy\SiteBundle\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SiteControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = self::createClient();
    }
    
    /**
     * @dataProvider provideUrlsGet
     * @group func
     * @group get
     * @group site
     * @group siteget
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
        $page = 1;
        $idVideo = 2;
        $idGenre = $idHashtag = $idLyrics = $idITW = 2;
        $idArtiste = 1;
        $slug = "test";

        return [
            ["/$page"],                                     
            ["/ajax/$page"],                                
            ["/video/$idVideo/$slug"] ,                        
            ["/video/$idVideo"] ,                                
            ["/artistes/$page"]  ,                       
            ["/artiste/$idArtiste/$slug/$page"]      ,           
            ["/artiste/$idArtiste"],                              
            //["/artiste"],                                   
            ["/genres/$page"]  ,                            
            ["/genre/$slug-$idGenre"]  ,                        
            ["/genre/$idGenre/$page/$slug"],
            //["/news"],                                       
            ["/credits"],                                    
            ["/contact"],                                    
            ["/contact/approval"],
            // ["/test"],                                      
            ["/list_artiste"],                               
            ["/list_alpha"],                                 
            ["/alphabet"],

            ["/tops/$page"],                            
            ["/genres_menu"],                               
            ["/video/paroles/$idVideo/$slug"],                         
            ["/itw/$idArtiste/$slug"],            
            //["/itw/$idITW/$slug"],                    
            ["/video/json/$idVideo"],
            ["/login"],
            ["/inscription/"],  
            ["/reset/request"],  
            ["/hashtags/"],                                  
            ["/hashtags/$page/$page"],                  
        ];
    }

    /**
     * @dataProvider provideUrlsRedirect
     * @group func
     * @group get
     * @group site
     */
    public function testRedirectSuccessful($url)
    {
        $this->client->request('GET', $url);
        $response = $this->client->getResponse();

        if($response->getStatusCode() != Response::HTTP_FOUND)
        var_dump($url, $response->getStatusCode());

        // var_dump($response->getContent());

        $this->assertSame(Response::HTTP_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function provideUrlsRedirect()
    {
        return [                                    
            ["/user/mimizikcom"],                            
            ["/user/mimizikcom/test"],                   
            ["/channel/mimizikcom"],                              
            ["/channel/mimizikcom/test"],                       
            ["/plugins/feedback.php"],
            ["/wp-login.php"],                               
            ["/hashta"],
            ["/logout"],
            // ["/inscription/confirmed"],
            // ["/inscription/check-email"]
        ];
    }

    /**
     * @group func
     * @group get
     * @group site
     * @throws AccessDeniedException
     */
    public function testConfirmed()
    {
        $this->client->request('GET', "/inscription/confirmed");
        $response = $this->client->getResponse();

        if($response->getStatusCode() != Response::HTTP_INTERNAL_SERVER_ERROR)
        var_dump("/inscription/confirmed", $response->getStatusCode());

        // var_dump($response->getContent());

        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $this->client->getResponse()->getStatusCode());
    }
}