<?php

namespace Spicy\FluxBundle\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FluxControllerTest extends WebTestCase
{

    /**
     * @dataProvider provideUrlsGet
     * @group func
     * @group flux
     */
    public function testGetSuccessful($url)
    {
        
        $client = self::createClient();
        //$this->logIn();
        $client->request('GET', $url);

        if($client->getResponse()->getStatusCode() != Response::HTTP_OK)
        var_dump($url, $client->getResponse()->getStatusCode());

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrlsGet()
    {
        return [
            ['/flux/videos'],                                
            ['/flux/videos_twitter'],                        
            ['/flux/retro'],                                 
            ['/flux/retro_twitter'],                         
            ['/flux/artistes'],                              
            ['/flux/index'],                                 
            ['/flux/videos_top'],                            
            ['/flux/videos_top_twitter'],                    
            ['/flux/lyrics'],                                
            ['/flux/itw'],                                   
            ['/flux/videos_json'],
        ];
    }

}