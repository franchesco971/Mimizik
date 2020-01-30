<?php

namespace Spicy\SiteBundle\Tests\Func;

use Spicy\RankingBundle\Entity\RankingType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Client;

class AdminControllerTest extends WebTestCase
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

    /**
     * @dataProvider provideUrls
     * @group func
     * @group admin
     * @group adminget
     */
    public function testPageIsSuccessful($url)
    {
        $this->client->request('GET', $url);

        $response = $this->client->getResponse();

        if ($response->getStatusCode() != Response::HTTP_OK) {
            var_dump($url, $response->getStatusCode());
        }

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function provideUrls()
    {
        $idArtiste = 2;
        $idVideo = 2;
        $idGenre = $idType = $idHashtag = $idLyrics = 2;
        $idITW = 1;

        return [
            ['/admin/'],
            ['/admin/video'],
            ['/admin/add_video'],
            ['/admin/update_video/' . $idVideo],
            // ['/admin/delete_video/'.$idVideo],
            ['/admin/artiste'],
            ['/admin/add_artiste'],
            ['/admin/update_artiste/' . $idArtiste],
            // ['/admin/delete_artiste/'.$idArtiste],
            ['/admin/type_video'],
            ['/admin/genre_musical'],
            ['/admin/add_genre_musical'],
            ['/admin/update_genre_musical/' . $idGenre],
            // ['/admin/delete_genre_musical/'.$idGenre],
            ['/admin/add_type_video'],
            ['/admin/update_type_video/' . $idType],
            // ['/admin/delete_type_video/'.$idType],
            // ['/admin/csv'],   ////TODO

            // ['/admin/hashtag/new'],


            ['/admin/hashtag/new_modal'],
            ['/admin/collaborateur/new_modal'],

            // ['/admin/hashtag/create'],
            // ['/admin/hashtag/edit/' . $idHashtag],
            //
            ['/admin/add_video_youtube'],

            ['/admin/test/fb'],
            ['/profil/'],
            ['/profil/edit'],
            ["/profil/change-password"],
            //["/hashtags/new"],        // DELETE
            //["/hashtags/create"],        // DELETE

            //['/admin/itw/{id}                                         '],

        ];
    }

    /**
     * @group func
     * @group ranking
     * @group wrong
     */
    public function testWrongPage()
    {
        $this->client->request('GET', '/classements/month/__id__');

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideUrls
     * @group func
     * @group ranking
     * @group wrong
     */
    public function testWrongShowID()
    {
        $this->client->request('GET', '/classements/show/2/0');

        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider provideUrls
     * @group func
     * @group ranking
     * @group wrong
     */
    public function testShowTypeAnnee()
    {
        $rankingType = RankingType::ANNEE;
        $this->client->request('GET', "/classements/show/$rankingType/2");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewall = 'main';
        $userManager = static::$kernel->getContainer()->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername('user_1');
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * @dataProvider provideUrlsPost
     * @group func
     * @group admin
     * @group post
     * @group adminpost
     */
    public function testPostSuccessful($url)
    {
        $this->client->request('POST', $url);

        $response = $this->client->getResponse();

        if ($response->getStatusCode() != Response::HTTP_OK) {
            var_dump($url, $this->client->getResponse()->getStatusCode());
        }

        //var_dump($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function provideUrlsPost()
    {
        $idVideo = $idGenre = 2;

        return [
            //['/admin/hashtag/create_modal'], referrer
            //['/admin/collaborateur/create_modal'], referrer
            ["/admin/delete_artiste/$idVideo"],
            ["/admin/delete_video/$idVideo"],
            // ['/admin/add_video_youtube'],
            ["/admin/delete_type_video/$idVideo"],
            ["/admin/delete_genre_musical/$idGenre"],
        ];
    }

    /**
     * @group func
     * @group ranking
     * @group wrong
     */
    public function testRankingWrong()
    {
        $rankingType = RankingType::ANNEE;
        $this->client->request('GET', "/classements/show/$rankingType/2");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @group func
     * @group admin
     * @group wrong
     * @group addvideowrong
     */
    public function testPostWrong()
    {
        $this->client->request('POST', "/admin/add_video", ['youtubeUrl' => 'test']);

        $response = $this->client->getResponse();

        if ($response->getStatusCode() != Response::HTTP_OK) {
            var_dump("/admin/add_video", $response->getStatusCode());
        }

        //var_dump($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @dataProvider provideUrlsGetWrong
     * @group func
     * @group admin
     * @group adminget
     * @group admingetwrong
     */
    public function testGetWrong($url)
    {
        $this->client->request('GET', $url);

        $response = $this->client->getResponse();

        if ($response->getStatusCode() != Response::HTTP_OK) {
            var_dump($url, $response->getStatusCode());
        }

        //var_dump($response->getContent());

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
    }

    public function provideUrlsGetWrong()
    {
        $idType = 2;

        return [
            ["/admin/update_type_video/$idType"],
        ];
    }
}
