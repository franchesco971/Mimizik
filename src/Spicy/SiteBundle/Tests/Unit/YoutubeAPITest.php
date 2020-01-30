<?php

namespace Spicy\SiteBundle\Tests\Unit;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Spicy\AppBundle\Entity\Channel;
use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Repository\VideoRepository;
use Spicy\SiteBundle\Services\ApprovalService;
use Spicy\SiteBundle\Services\YoutubeAPI;

class YoutubeAPITest extends TestCase
{
    private $em;
    private $logger;
    private $approvalService;

    private $testedInstance;
    private $subscriptions = [];
    private $videos;
    private $videoJson;

    protected function setUp()
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->approvalService = $this->createMock(ApprovalService::class);

        $this->testedInstance = new YoutubeAPI('123456', $this->logger, $this->em, $this->approvalService);
        // $this->testedInstanceMock = $this->createMock(YoutubeAPI::class);
        $this->testedInstanceMock = $this->getMockBuilder(YoutubeAPI::class)
            ->setConstructorArgs(['123456', $this->logger, $this->em, $this->approvalService])
            ->setMethods(['getJSONObject'])
            ->getMock();
        // new YoutubeAPI('123456', $this->logger, $this->em, $this->approvalService);

        $this->subscriptions = [];
        $this->subscriptions = [
            'items' => [
                [
                    'snippet'=>[
                        'resourceId'=>[
                            'channelId'=> 2
                        ],
                        'title' => 'mimizikcom'
                    ],
                    'contentDetails' => [
                        'totalItemCount'=> 2
                    ]
                ]
            ],
            'nextPageToken' => 'token',
            'pageInfo'=>[
                'totalResults' => 10,
                'resultsPerPage' => 2,
            ]
        ];

        $this->videos = [];
        $this->videos['items'][] = [
            'id' => [
                'kind' => "youtube#video",
                'videoId' => 99
            ]
        ];

        $this->videoJson['items'][] = [
            'contentDetails' => [
                'duration' => 'PT5M0S'
            ],
            'snippet' => [
                'description' => 'description test',
                'title' => 'title test',
                'channelTitle' => 'mimizikcom',
            ],
            'id' => 'azerty',         
        ];
    }

    /** 
     * @group unit
     * @group youtube
     * @group getVideoserror
     */
    public function testGetVideosError() 
    {
        //$subscriptions['items'] = null;

        $this->logger->expects($this->exactly(2))->method("error");
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("[YOUTUBEAPI] getJSONObject : L'api ne reponds pas");
        
        $this->testedInstance->getVideos();
    }

    /** 
     * @dataProvider getVideosErrorProvider
     * 
     * @group unit
     * @group youtube
     * @group getVideoserror2
     */
    public function testGetVideosError2($subscriptions, $videos, $videoJson, $message) 
    {
        $video = $this->createMock(Video::class);
        
        $videoRepo = $this->createMock(VideoRepository::class);
        $channelRepo = $this->createMock(ObjectRepository::class);
        $videoRepo->expects($this->any())->method('findOneBy')->willReturn($video);

        $url = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet%2CcontentDetails&channelId=UCygqmomnv_HLr-Df0LlxsiQ&key=123456";
        $urlVideos = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=2&maxResults=10&order=date&publishedAfter=2018-11-01T00%3A00%3A00Z&key=123456";
        $urlData = "https://www.googleapis.com/youtube/v3/videos?id=99&key=123456&part=snippet,topicDetails%2CcontentDetails";

        // $this->testedInstanceMock->expects($this->any())->method('_construct')->with('123456', $this->logger, $this->em, $this->approvalService)->willReturn('{"adadzazd":"dazdazdaz"}');
        // var_dump($this->subscriptions);
        $map = [
            [$url, array_merge($this->subscriptions, $subscriptions)],
            [$urlVideos, array_merge($this->videos, $videos)],
            [$urlData, array_merge($this->videoJson, $videoJson)],
        ];

        $this->testedInstanceMock->expects($this->any())->method('getJSONObject')->will($this->returnValueMap($map));

        $repoMap = [
            [Channel::class, $channelRepo],
            [Video::class, $videoRepo],
        ];

        $this->em->expects($this->any())->method('getRepository')->will($this->returnValueMap($repoMap));

        $this->logger->expects($this->any())->method("error");
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($message);
        
        $this->testedInstanceMock->getVideos();
    }

    public function getVideosErrorProvider()
    {
        $subscriptions['items'] = null;
        $videos['items'] = null;
        
        return [
            [$subscriptions, [], [], "[YOUTUBEAPI] getVideos : [fetchSubscriptions] : No items"],
            [[], $videos, [], "[YOUTUBEAPI] getVideos : [fetchSubscriptions] : searchVideos No items error"],
            // [6, 4, "up"],
            // [5, 9, "down"],
            // [7, 7, "forward"],
        ];
    }

    /** 
     * @dataProvider getVideosProvider
     * 
     * @group unit
     * @group youtube
     * @group getVideos
     */
    public function testGetVideos($videoRepo, $nbApprovals) 
    {
        // $video = $this->createMock(Video::class);
        //$videoRepo = $this->createMock(VideoRepository::class);
        $channelRepo = $this->createMock(ObjectRepository::class);

        $url = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet%2CcontentDetails&channelId=UCygqmomnv_HLr-Df0LlxsiQ&key=123456";
        $urlVideos = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=2&maxResults=10&order=date&publishedAfter=2018-11-01T00%3A00%3A00Z&key=123456";
        $urlData = "https://www.googleapis.com/youtube/v3/videos?id=99&key=123456&part=snippet,topicDetails%2CcontentDetails";

        // $this->testedInstanceMock->expects($this->any())->method('_construct')->with('123456', $this->logger, $this->em, $this->approvalService)->willReturn('{"adadzazd":"dazdazdaz"}');
        $map = [
            [$url, $this->subscriptions],
            [$urlVideos, $this->videos],
            [$urlData, $this->videoJson],
        ];
        $this->testedInstanceMock->expects($this->any())->method('getJSONObject')->will($this->returnValueMap($map));

        $repoMap = [
            [Channel::class, $channelRepo],
            [Video::class, $videoRepo],
        ];

        // $this->em->expects($this->any())->method('getRepository')->with(Channel::class)->willReturn($channelRepo);

        $this->em->expects($this->any())->method('getRepository')->will($this->returnValueMap($repoMap));

        // $this->testedInstanceMock->_construct('123456', $this->logger, $this->em, $this->approvalService);

        // $this->testedInstance = new YoutubeAPI('123456', $this->logger, $this->em, $this->approvalService);
        
        // $errors = $this->testedInstance->getVideos();
        $errors = $this->testedInstanceMock->getVideos();
        // var_dump($errors);
        $this->assertEquals(3, count($errors));
        $this->assertEquals(" 10 abonnements", $errors[0]);
        $this->assertEquals(" 1 Channels", $errors[1]);
        $this->assertEquals(" $nbApprovals Approvals", $errors[2]);
    }

    public function getVideosProvider()
    {
        $subscriptions = $this->subscriptions;
        // var_dump($subscriptions);
        $subscriptions['items'] = null;
        $videos['items'] = null;
        $nbApprovals = 1;
        $video = $this->createMock(Video::class);
        $videoRepo = $this->createMock(VideoRepository::class);
        $videoRepo2 = $this->createMock(VideoRepository::class);
        $videoRepo2->expects($this->any())->method('findOneBy')->with(['url' => 'azerty'])->willReturn($video);
        
        return [
            [$videoRepo, $nbApprovals],
            [$videoRepo2, 0],
            // [6, 4, "up"],
            // [5, 9, "down"],
            // [7, 7, "forward"],
        ];
    }

    /** 
     * @group unit
     * @group youtube
     * @group getByYoutubeId
     */
    public function testGetByYoutubeId() 
    {
        $url = "https://www.googleapis.com/youtube/v3/subscriptions?part=snippet%2CcontentDetails&channelId=UCygqmomnv_HLr-Df0LlxsiQ&key=123456";
        $urlVideos = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=2&maxResults=10&order=date&publishedAfter=2018-11-01T00%3A00%3A00Z&key=123456";
        $urlData = "https://www.googleapis.com/youtube/v3/videos?id=azerty&key=123456&part=snippet,topicDetails%2CcontentDetails";

        $map = [
            [$url, $this->subscriptions],
            [$urlVideos, $this->videos],
            [$urlData, $this->videoJson],
        ];
        $this->testedInstanceMock->expects($this->any())->method('getJSONObject')->will($this->returnValueMap($map));

        $this->testedInstanceMock->getByYoutubeId('azerty');
    }
}