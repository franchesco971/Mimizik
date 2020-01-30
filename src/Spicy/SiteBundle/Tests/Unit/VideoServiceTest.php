<?php

namespace Spicy\SiteBundle\Tests\Unit;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Spicy\RankingBundle\Entity\Ranking;
use Spicy\RankingBundle\Entity\RankingType;
use Spicy\RankingBundle\Entity\Repository\RankingRepository;
use Spicy\RankingBundle\Entity\Repository\VideoRankingRepository;
use Spicy\RankingBundle\Entity\VideoRanking;
use Spicy\SiteBundle\Entity\Artiste;
use Spicy\SiteBundle\Entity\ArtisteRepository;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Repository\VideoRepository;
use Spicy\SiteBundle\Services\ParseurXMLYoutube;
use Spicy\SiteBundle\Services\VideoService;

class VideoServiceTest extends TestCase
{
    private $em;
    private $logger;
    private $parseur;

    private $testedInstance;

    protected function setUp()
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->parseur = $this->createMock(ParseurXMLYoutube::class);

        $this->testedInstance = new VideoService($this->em, $this->logger, $this->parseur);
    }

    /**
     * @group unit
     * @group site
     * @group increment
     */
    public function testIncrement()
    {
        $genre = $this->createMock(GenreMusical::class);
        $ranking = $this->createMock(Ranking::class);
        $rankingType = $this->createMock(RankingType::class);
        $genre->expects($this->any())->method('getId')->willReturn(1);

        $genres = [
            $genre
        ];
        $video = $this->createMock(Video::class);
        $videoRanking = $this->createMock(VideoRanking::class);
        $video->method('getId')->willReturn(2);

        $this->assertSame('127.0.0.2', $_SERVER['REMOTE_ADDR']);

        $video->expects($this->any())->method('getGenreMusicaux')->willReturn($genres);

        $videoRanking->expects($this->any())->method('getVideo')->willReturn($video);

        $videos = [$video];
        $videoRankings = [$videoRanking];

        $rankingRepo = $this->createMock(RankingRepository::class);
        $rankingTypeRepo = $this->createMock(ObjectRepository::class);
        $videoRankingRepo = $this->createMock(VideoRankingRepository::class);
        $videoRepo = $this->createMock(VideoRepository::class);

        // // $rankingRepo->expects($this->any())->method('getByDate')->with(RankingType::MOIS)->willReturn($ranking);
        // // $rankingRepo->expects($this->any())->method('getByDate')->with(RankingType::ANNEE)->willReturn($yearRanking);

        $ranking->expects($this->any())->method('getRankingType')->willReturn($rankingType);
        $ranking->expects($this->any())->method('getVideoRankings')->willReturn($videoRankings);
        

        // $rankingType->expects($this->any())->method('getId')->willReturn(98);
        $videoRankingRepo->expects($this->exactly(2))->method('getOne');
        $rankingRepo->expects($this->exactly(2))->method('getByDate')->willReturn($ranking);
        // $rankingRepo->expects($this->any())->method('getPreviousRanking')->willReturn($ranking);
        // $videoRepo->expects($this->any())->method('getTopByDate')->willReturn($videos);
        // $video->expects($this->any())->method('getVideoRankings')->willReturn($videoRankings);

        // //$ranking->expects($this->any())->method('getId')->willReturn(99);
        // $ranking->expects($this->any())->method('getRankingType')->willReturn($rankingType);
        // //$yearRanking->expects($this->any())->method('getRankingType')->willReturn($rankingType);

        $map = array(
            array(Ranking::class, $rankingRepo),
            array(RankingType::class, $rankingTypeRepo),
            [VideoRanking::class, $videoRankingRepo],
            [Video::class, $videoRepo],
        );

        // // $this->em->->method('getRepository')->withConsecutive(Ranking::class, RankingType::class)
        // // ->willReturnOnConsecutiveCalls($rankingRepo,$rankingTypeRepo);

        $this->em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($map));

        // $rankingTypeMap = [
        //     [RankingType::MOIS, $ranking],
        //     [RankingType::ANNEE, $yearRanking]
        // ];

        // $rankingRepo->expects($this->any())->method('getByDate')->will($this->returnValueMap($rankingTypeMap));

        // $videoRankingRepo->method('getOne')->willReturn($videoRanking);
        //$this->logger->expects($this->once())->method("error")->with('[getRanking] Pas de classement en base');

        $this->testedInstance->increment($video);
    }

    /**
     * @group unit
     * @group site
     * @group increment
     */
    public function testIncrementRetro()
    {
        $genre = $this->createMock(GenreMusical::class);
        // $ranking = $this->createMock(Ranking::class);
        // $rankingType = $this->createMock(RankingType::class);
        $genre->expects($this->any())->method('getId')->willReturn(GenreMusical::RETRO);

        $genres = [$genre];
        $video = $this->createMock(Video::class);
        // $videoRanking = $this->createMock(VideoRanking::class);
        $video->expects($this->once())->method('getId')->willReturn(2);   
        $video->expects($this->once())->method('getGenreMusicaux')->willReturn($genres);     
        $this->logger->expects($this->once())->method("info")->with("[increment] This is a retro video : 2");

        $this->testedInstance->increment($video);
    }

    /**
     * @group unit
     * @group site
     * @group increment
     */
    // public function testIncrementErrorMonth()
    // {
    //     $genre = $this->createMock(GenreMusical::class);
    //     $ranking = $this->createMock(Ranking::class);
    //     $rankingType = $this->createMock(RankingType::class);
    //     $genre->expects($this->any())->method('getId')->willReturn(1);

    //     $genres = [$genre];
    //     $video = $this->createMock(Video::class);
    //     $videoRanking = $this->createMock(VideoRanking::class);
    //     $video->method('getId')->willReturn(2);

    //     $this->assertSame('127.0.0.2', $_SERVER['REMOTE_ADDR']);

    //     $video->expects($this->any())->method('getGenreMusicaux')->willReturn($genres);

    //     $videoRanking->expects($this->any())->method('getVideo')->willReturn($video);

    //     // $videos = [$video];
    //     $videoRankings = [$videoRanking];

    //     $rankingRepo = $this->createMock(RankingRepository::class);
    //     $rankingTypeRepo = $this->createMock(ObjectRepository::class);
    //     $videoRankingRepo = $this->createMock(VideoRankingRepository::class);
    //     // $videoRepo = $this->createMock(VideoRepository::class);

    //     $ranking->expects($this->any())->method('getRankingType')->willReturn($rankingType);
    //     $ranking->expects($this->any())->method('getVideoRankings')->willReturn($videoRankings);
        
    //     // $videoRankingRepo->expects($this->exactly(1))->method('getOne');
    //     $rankingRepo->expects($this->exactly(1))->method('getByDate')->with(RankingType::MOIS)->willReturn(null);
    //     // $rankingRepo->expects($this->any())->method('getPreviousRanking')->willReturn($ranking);
    //     // $videoRepo->expects($this->any())->method('getTopByDate')->willReturn($videos);
    //     // $video->expects($this->any())->method('getVideoRankings')->willReturn($videoRankings);

    //     $map = array(
    //         array(Ranking::class, $rankingRepo),
    //         array(RankingType::class, $rankingTypeRepo),
    //         [VideoRanking::class, $videoRankingRepo],
    //         // [Video::class, $videoRepo],
    //     );

    //     $this->em->expects($this->any())
    //         ->method('getRepository')
    //         ->will($this->returnValueMap($map));

    //     $this->logger->expects($this->at(0))->method("error")->with('[getRanking] Pas de classement en base'); 
    //     $this->logger->expects($this->at(1))->method("error")->with('[increment] Erreur get month ranking');    

    //     // $errorMap = array(
    //     //     [VideoRanking::class, $videoRankingRepo],
    //     //     [Video::class, $videoRepo],
    //     // );

    //     // $this->logger->expects($this->any())
    //     //     ->method('error')
    //     //     ->will($this->returnValueMap($errorMap));

    //     $this->testedInstance->increment($video);
    // }

    /**
     * @group unit
     * @group site
     * @group increment
     * @group testIncrementErrorYear
     */
    public function testIncrementErrorYear()
    {
        $genre = $this->createMock(GenreMusical::class);
        $ranking = $this->createMock(Ranking::class);
        $rankingType = $this->createMock(RankingType::class);
        $genre->expects($this->any())->method('getId')->willReturn(1);

        $genres = [$genre];
        $video = $this->createMock(Video::class);
        $videoRanking = $this->createMock(VideoRanking::class);
        $video->method('getId')->willReturn(2);

        $this->assertSame('127.0.0.2', $_SERVER['REMOTE_ADDR']);

        $video->expects($this->any())->method('getGenreMusicaux')->willReturn($genres);

        $videoRanking->expects($this->any())->method('getVideo')->willReturn($video);
        $videoRanking->expects($this->exactly(2))->method('getPosition')->willReturn(99);

        $videos = [$video];
        $videoRankings = [$videoRanking];

        $rankingRepo = $this->createMock(RankingRepository::class);
        $rankingTypeRepo = $this->createMock(ObjectRepository::class);
        $videoRankingRepo = $this->createMock(VideoRankingRepository::class);
        $videoRepo = $this->createMock(VideoRepository::class);

        $ranking->expects($this->any())->method('getRankingType')->willReturn($rankingType);
        $ranking->expects($this->any())->method('getVideoRankings')->willReturn($videoRankings);
        
        // $videoRankingRepo->expects($this->exactly(1))->method('getOne');
        // $rankingRepo->expects($this->at(0))->method('getByDate')->with(RankingType::MOIS)->willReturn($ranking);
        // $rankingRepo->expects($this->at(1))->method('getByDate')->with(RankingType::ANNEE)->willReturn(null);
        $rankingRepo->expects($this->any())->method('getPreviousRanking')->willReturn($ranking);
        $videoRepo->expects($this->any())->method('getTopByDate')->willReturn($videos);
        $video->expects($this->any())->method('getVideoRankings')->willReturn($videoRankings);

        $mapDate = array(
            [RankingType::MOIS, $ranking],
            [RankingType::ANNEE, null],
        );

        $rankingRepo->expects($this->any())
            ->method('getByDate')
            ->will($this->returnValueMap($mapDate));

        $map = array(
            array(Ranking::class, $rankingRepo),
            array(RankingType::class, $rankingTypeRepo),
            [VideoRanking::class, $videoRankingRepo],
            [Video::class, $videoRepo],
        );

        $this->em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($map));

        // $this->logger->method("error")->with('[getRanking] Pas de classement en base');  
        //$this->logger->method("error")->with('[increment] Erreur get Year ranking');   
        
        $this->testedInstance->increment($video);
    }

    /**
     * @group unit
     * @group site
     */
    // public function testIsRetro()
    // {
    //     $genre = $this->createMock(GenreMusical::class);
    //     $genre->expects($this->once())->method('getId')->willReturn(GenreMusical::RETRO);

    //     $genres = [
    //         $genre
    //     ];

    //     $video = $this->createMock(Video::class);

    //     $video->expects($this->once())->method('getGenreMusicaux')->willReturn($genres);

    //     $testedInstance = new VideoService($this->em, $this->logger, $this->parseur);
    //     $this->assertTrue($testedInstance->isRetro($video));
    // }

    // /**
    //  * @group unit
    //  * @group site
    //  */
    // public function testIsRetroFalse()
    // {
    //     $genre = $this->createMock(GenreMusical::class);
    //     $genre->expects($this->any())->method('getId')->willReturn(1);

    //     $genres = [];

    //     $video = $this->createMock(Video::class);

    //     $video->expects($this->once())->method('getGenreMusicaux')->willReturn($genres);

    //     $testedInstance = new VideoService($this->em, $this->logger, $this->parseur);
    //     $this->assertFalse($testedInstance->isRetro($video));
    // }

    /**
     * @group unit
     * @group site
     * @group setPositions
     */
    // public function testSetPositions()
    // {
    //     $ranking = $this->createMock(Ranking::class);
    //     $video = $this->createMock(Video::class);
    //     $videoRanking = $this->createMock(VideoRanking::class);
    //     $rankingRepo = $this->createMock(RankingRepository::class);
    //     $videoRepo = $this->createMock(VideoRepository::class);

    //     $videos = [$video];
    //     $videoRankings = [$videoRanking];

    //     // $this->em->expects($this->any())
    //     //     ->method('getRepository')->with(Ranking::class)->willReturn($rankingRepo);
    //     $video->method('getVideoRankings')->willReturn($videoRankings);
    //     $ranking->method('getVideoRankings')->willReturn($videoRankings);
    //     $videoRanking->method('getVideo')->willReturn($video);

    //     $videoRepo->method('getTopByDate')->willReturn($videos);

    //     $rankingRepo->method('getPreviousRanking')->willReturn($ranking);

    //         $map = [
    //             [Ranking::class, $rankingRepo],
    //             [Video::class, $videoRepo],
    //         ];
    
    //         $this->em->expects($this->any())
    //             ->method('getRepository')
    //             ->will($this->returnValueMap($map));

    //     $this->testedInstance->setPositions($ranking);
    // }

    /**
     * @group unit
     * @group site
     * @group setPositions
     */
    public function testSetPositionsError()
    {
        $ranking = null;
        // $video = $this->createMock(Video::class);
        // $videoRanking = $this->createMock(VideoRanking::class);
        // $rankingRepo = $this->createMock(RankingRepository::class);
        // $videoRepo = $this->createMock(VideoRepository::class);

        // $videos = [$video];
        // $videoRankings = [$videoRanking];

        // $video->method('getVideoRankings')->willReturn($videoRankings);
        // $ranking->method('getVideoRankings')->willReturn($videoRankings);
        // $videoRanking->method('getVideo')->willReturn($video);

        // $videoRepo->method('getTopByDate')->willReturn($videos);

        // $rankingRepo->method('getPreviousRanking')->willReturn($ranking);

        // $map = [
        //     [Ranking::class, $rankingRepo],
        //     [Video::class, $videoRepo],
        // ];

        // $this->em->expects($this->any())
        //     ->method('getRepository')
        //     ->will($this->returnValueMap($map));
        $this->logger->expects($this->once())->method("error")->with("[setPositions] no ranking");

        $this->testedInstance->setPositions($ranking);
    }

    /**
     * @group unit
     * @group site
     * @group setPositions
     */
    public function testSetPositionsErrorVideos()
    {
        $ranking = $this->createMock(Ranking::class);
        // $video = $this->createMock(Video::class);
        $videoRanking = $this->createMock(VideoRanking::class);
        // $rankingRepo = $this->createMock(RankingRepository::class);
        $videoRepo = $this->createMock(VideoRepository::class);

        $videos = [];
        $videoRankings = [$videoRanking];

        // $video->method('getVideoRankings')->willReturn($videoRankings);
        $ranking->method('getVideoRankings')->willReturn($videoRankings);
        // $videoRanking->method('getVideo')->willReturn($video);

        $videoRepo->method('getTopByDate')->willReturn($videos);

        // $rankingRepo->method('getPreviousRanking')->willReturn(null);

        $map = [
            // [Ranking::class, $rankingRepo],
            [Video::class, $videoRepo],
        ];

        $this->em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($map));
            
        $this->logger->expects($this->once())->method("error")->with("[setPositions] no videos");

        $this->testedInstance->setPositions($ranking);
    }

    /**
     * @group unit
     * @group site
     * @group setPositions
     */
    public function testSetPositionsErrorPrev()
    {
        $ranking = $this->createMock(Ranking::class);
        $video = $this->createMock(Video::class);
        $videoRanking = $this->createMock(VideoRanking::class);
        $rankingRepo = $this->createMock(RankingRepository::class);
        $videoRepo = $this->createMock(VideoRepository::class);

        $videos = [$video];
        $videoRankings = [$videoRanking];

        // $video->method('getVideoRankings')->willReturn($videoRankings);
        $ranking->method('getVideoRankings')->willReturn($videoRankings);
        // $videoRanking->method('getVideo')->willReturn($video);

        $videoRepo->method('getTopByDate')->willReturn($videos);

        $rankingRepo->method('getPreviousRanking')->willReturn(null);

        $map = [
            [Ranking::class, $rankingRepo],
            [Video::class, $videoRepo],
        ];

        $this->em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($map));

        $this->logger->expects($this->once())->method("error")->with("[setPositions] no previousRanking");

        $this->testedInstance->setPositions($ranking);
    }

    /**
     * @dataProvider setIconsProvider
     * 
     * @group unit
     * @group site
     * @group setIcons
     */
    public function testSetIcons($previousPosition, $position, $expectedResult)
    {               
        $result = $this->testedInstance->setIcons($previousPosition, $position);

        $this->assertEquals($result, $expectedResult);
    }

    public function setIconsProvider()
    {
        return [
            [null, null, "add"],
            [null, 99, "add"],
            [6, 4, "up"],
            [5, 9, "down"],
            [7, 7, "forward"],
        ];
    }

     /** 
     * @dataProvider createRankingProvider 
     * 
     * @group unit
     * @group site
     * @group createRanking
     */
    public function testCreateRanking($type, $expectedResult)
    {               
        $rankingType = $this->createMock(RankingType::class);
        $rankingTypeRepo = $this->createMock(ObjectRepository::class);
        $rankingTypeRepo->method('find')->willReturn(null);        

        $map = [
            [RankingType::MOIS, $rankingType],
            [RankingType::ANNEE, $rankingType],
        ];

        $rankingTypeRepo->expects($this->any())
            ->method('find')
            ->will($this->returnValueMap($map));

        $this->em->method('getRepository')->with(RankingType::class)->willReturn($rankingTypeRepo);

        $result = $this->testedInstance->createRanking($type);

        $this->assertEquals($result->getStartRanking(), $expectedResult->getStartRanking());
    }

    public function createRankingProvider()
    {
        $rankingMois = $this->createMock(Ranking::class);
        $rankingAnnee = $this->createMock(Ranking::class);

        $rankingMois->method("getStartRanking")->willReturn((new \DateTime("first day of this month"))->setTime(0, 0, 1));
        $rankingAnnee->method("getStartRanking")->willReturn((new \DateTime("first day of this year"))->setTime(0, 0, 1));

        return [
            [RankingType::MOIS, $rankingMois],
            [RankingType::ANNEE, $rankingAnnee],
        ];
    }

    /** 
     * @group unit
     * @group site
     * @group createRanking
     */
    public function testCreateRankingNull()
    {
        $result = $this->testedInstance->createRanking(Rankingtype::JOUR);

        $this->assertEquals($result, null);
    }

    /** 
     * @group unit
     * @group site
     * @group getRanking
     */
    public function testGetRankingErrorPrevious()
    {
        $ranking = $this->createMock(Ranking::class);

        $rankingRepo = $this->createMock(RankingRepository::class);
        $rankingTypeRepo = $this->createMock(ObjectRepository::class);

        $ranking->method('getStartRanking')->willReturn(new \DateTime("now"));

        $rankingRepo->expects($this->once())->method('getByDate')->willReturn(null);

        // $this->em->method('getRepository')->with(Ranking::class)->willReturn($rankingRepo);

        $map = [
            [Ranking::class, $rankingRepo],
            [RankingType::class, $rankingTypeRepo],
        ];

        $this->em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValueMap($map));

        $this->logger->expects($this->once())->method("error")->with("[getRanking] Erreur getPreviousRanking : (select max(ra.id) from SpicyRankingBundle:Ranking ra "
        . "where ra.id< ID  AND ra.rankingType= RT.ID ");
        
        $result = $this->testedInstance->getRanking(Rankingtype::MOIS);        
    }

    /** 
     * @group unit
     * @group site
     * @group compareRanking
     */
    public function testCompareRankingError()
    {
        $videoRanking = $this->createMock(VideoRanking::class);
        $previousVideoRanking = $this->createMock(VideoRanking::class);
        $ranking = $this->createMock(Ranking::class);
        $video = $this->createMock(Video::class);
        $video2 = $this->createMock(Video::class);

        $video->expects($this->once())->method('getId')->willReturn(1);
        $video2->expects($this->once())->method('getId')->willReturn(2);

        $ranking->expects($this->once())->method('getVideoRankings')->willReturn([$previousVideoRanking]);
        $videoRanking->expects($this->once())->method('getVideo')->willReturn($video);
        $previousVideoRanking->expects($this->once())->method('getVideo')->willReturn($video2);

        $this->logger->expects($this->once())->method("info")->with("la video n'est pas presente dans le classement precedent");
        
        $result = $this->testedInstance->compareRanking($videoRanking, $ranking);
    }

    /** 
     * @group unit
     * @group site
     * @group incrementVideoRanking
     */
    public function testIncrementVideoRanking()
    {
        $video = $this->createMock(Video::class);
        $ranking = $this->createMock(Ranking::class);
        $videoRanking = $this->createMock(VideoRanking::class);

        $videoRankingRepo = $this->createMock(VideoRankingRepository::class);

        $videoRanking->expects($this->once())->method('getNbVu');

        $videoRankingRepo->expects($this->any())->method('getOne')->willReturn($videoRanking);

        $this->em->expects($this->any())->method('getRepository')->willReturn($videoRankingRepo);
        
        $this->testedInstance->incrementVideoRanking($video, $ranking);
    }

    /** 
     * @dataProvider getIconProvider
     * 
     * @group unit
     * @group site
     * @group getIcon
     */
    public function testGetIcon($videoRanking, $previousRanking, $position, $expectedResult)
    {
        $video = $this->createMock(Video::class);
        $video2 = $this->createMock(Video::class);
        $previousVideoRanking = $this->createMock(VideoRanking::class);

        $previousRanking->expects($this->exactly(2))->method('getVideoRankings')->willReturn([]);
        
        $result = $this->testedInstance->getIcon($videoRanking, $previousRanking, $position);

        $this->assertEquals($result, $expectedResult);
    }

    public function getIconProvider()
    {
        $ranking = $this->createMock(Ranking::class);
        $videoRanking = $this->createMock(VideoRanking::class);
        $videoRanking2 = $this->createMock(VideoRanking::class);
        $position = 99;

        $videoRanking->expects($this->any())->method("getIcon")->willReturn(null);
        $videoRanking2->expects($this->any())->method("getIcon")->willReturn('ico');       

        return [
            [$videoRanking, $ranking, $position, 'add'],
            [$videoRanking2, $ranking, $position, 'ico'],
        ];
    }

    
    /** 
     * @dataProvider getCleanLinkProvider
     * 
     * @group unit
     * @group site
     * @group getCleanLink
     */
    public function testGetCleanLink($link, $expectedResult)
    {
        $result = $this->testedInstance->getCleanLink($link);

        $this->assertEquals($result, $expectedResult);
    }

    public function getCleanLinkProvider()
    {
        return [
            ["https://mimizik.com", 'http://mimizik.com'],
            ["https://app_dev.php/mimizik.com", 'http://mimizik.com'],
        ];
    }

    
    /** 
     * @dataProvider getMessageProvider
     * 
     * @group unit
     * @group site
     * @group getMessage
     */
    public function testGetMessage($type, $expectedResult)
    {
        $video = $this->createMock(Video::class);
        $video->expects($this->any())->method('getNomArtistes')->willReturn("Test");
        $video->expects($this->any())->method('getTitre')->willReturn("titre");
        $video->expects($this->any())->method('getTitre')->willReturn("description");
        
        $message = $this->testedInstance->getMessage($video, $type);

        $this->assertEquals($message, $expectedResult);
    }

    public function getMessageProvider()
    {
        return [
            [VideoService::NEW_VIDEO, "Nouveau titre sur Mimizik.com : Test - titre.  #mimizik"],
            [VideoService::TOP_VIDEO, "Vidéo #Top10mimizik >> Test - titre.  #mimizik"],
        ];
    }
}
