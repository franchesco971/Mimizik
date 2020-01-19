<?php

namespace Spicy\RankingBundle\Tests\Func;

use Spicy\RankingBundle\Entity\RankingType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RankingControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     * @group func
     * @group ranking
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls()
    {
        return [
            ['/classements/month'],
            ['/classements/last'],
            ['/classements/show/2/2'],
            ['/classements/year'],
            ['/classements/side/2'],
        ];
    }

    /**
     * @group func
     * @group ranking
     * @group wrong
     */
    public function testWrongPage()
    {
        $client = self::createClient();
        $client->request('GET', '/classements/month/__id__');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @group func
     * @group ranking
     * @group wrong
     */
    public function testWrongShowID()
    {
        $client = self::createClient();
        $client->request('GET', '/classements/show/2/0');

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    /**
     * @group func
     * @group ranking
     * @group wrong
     */
    public function testShowTypeAnnee()
    {
        $client = self::createClient();
        $rankingType = RankingType::ANNEE;
        $client->request('GET', "/classements/show/$rankingType/2");

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
