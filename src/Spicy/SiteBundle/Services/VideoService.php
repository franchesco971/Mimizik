<?php

namespace Spicy\SiteBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Entity\Artiste;
use Spicy\RankingBundle\Entity\Ranking;
use Spicy\RankingBundle\Entity\RankingType;
use Spicy\RankingBundle\Entity\VideoRanking;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Services\ParseurXMLYoutube;
use Psr\Log\LoggerInterface;

class VideoService
{
    protected $em;
    protected $logger;
    protected $parser;
    protected $deniedIps;

    const TOP_VIDEO = 1;
    const NEW_VIDEO = 2;

    public function __construct(array $deniedIps, EntityManagerInterface $entityManager, LoggerInterface $logger, ParseurXMLYoutube $parser)
    {
        $this->em = $entityManager;
        $this->logger = $logger;
        $this->parser = $parser;
        $this->deniedIps = $deniedIps;
    }

    /**
     * Undocumented function
     * @TODO déplacer dans service
     * @param Video $video
     * @return void
     */
    public function increment(Video $video)
    {
        if ($this->isRetro($video)) {
            $this->logger->info("[increment] This is a retro video ", ['id' => $video->getId()]);
            return;
        }
        
        $ipInterdites = $this->deniedIps;

        $enable = isset($_SERVER['REMOTE_ADDR']) ? !in_array($_SERVER['REMOTE_ADDR'], $ipInterdites) : false;

        if (!$enable) {
            $this->logger->error("[increment] Unauthorized IP ", ['REMOTE_ADDR' => $_SERVER['REMOTE_ADDR']]);
            return;
        }

        //$nbVu = 0;
        $ranking = $this->getRanking(RankingType::MOIS);
        $yearRanking = $this->getRanking(RankingType::ANNEE);

        $this->incrementVideoRanking($video, $ranking);
        $this->incrementVideoRanking($video, $yearRanking);
    }

    /**
     * @return boolean
     */
    public function isRetro(Video $video): bool
    {
        $genres = $video->getGenreMusicaux();
        foreach ($genres as $genre) {
            $idGenre = $genre->getId();
            if ($idGenre == GenreMusical::RETRO) {
                return true;
            }
        }

        return false;
    }

    /**
     * getRanking
     *
     * @param integer $type
     * @return Ranking
     */
    public function getRanking($type = RankingType::MOIS)
    {
        $rankingRepository = $this->em->getRepository(Ranking::class);
        
        $ranking = $rankingRepository->getByDate($type);

        if ($ranking) {
            return $ranking;
        }

        $this->logger->info("[getRanking] Creation d'un ranking de type $type ");
        $ranking = $this->createRanking($type);

        $now = new \DateTime("now");
        $this->logger->info('[getRanking] Crée un nouveau classement ' . $ranking->getStartRanking()->format('Y-m-d H:i:sP') .
        '/ ' . $ranking->getEndRanking()->format('Y-m-d H:i:sP') . ' à ' . $now->format('Y-m-d H:i:sP'));

        $previousRanking = $rankingRepository->getPreviousRanking($ranking);

        /**** fige les positions du classement précédent **/
        if (!$previousRanking) {
            $this->logger->error("[getRanking] Erreur getPreviousRanking : (select max(ra.id) from SpicyRankingBundle:Ranking ra "
                . "where ra.id< ID  AND ra.rankingType= RT.ID ");
            return $ranking;
        }

        $this->setPositions($previousRanking);

        return $ranking;
    }

    /**
     * createRanking
     *
     * @param integer $type
     * @return Ranking|null
     */
    public function createRanking($type)
    {
        if ($type == RankingType::JOUR) {
            return null;
        }
        
        $ranking = new Ranking();
        $dateRanking = new \DateTime("now");
        if ($type == RankingType::MOIS) {
            $startRanking = new \DateTime("first day of this month");
            $endRanking = new \DateTime("first day of next month");
        } elseif ($type == RankingType::ANNEE) {
            $startRanking = new \DateTime("first day of this year");
            $endRanking = new \DateTime("first day of next year");
        }

        $startRanking->setTime(0, 0, 1);
        $endRanking->setTime(0, 0, 0);
        $rankingType = $this->em->getRepository(RankingType::class)->find($type);

        $ranking->setDateRanking($dateRanking)
            ->setStartRanking($startRanking)
            ->setEndRanking($endRanking)
            ->setRankingType($rankingType);

        $this->em->persist($ranking);
        $this->em->flush();

        return $ranking;
    }

    public function createVideoRanking($ranking, $video)
    {
        $videoRanking = new VideoRanking();
        $videoRanking->setRanking($ranking);
        $videoRanking->setVideo($video);
        $videoRanking->setNbVu(1);

        return $videoRanking;
    }

    /**
     * setPositions
     *
     * @param Ranking|null $ranking
     * @return void
     */
    public function setPositions($ranking)
    {
        if (!$ranking) {
            $this->logger->error("[setPositions] no ranking");
            return;
        }

        $videos = $this->em->getRepository(Video::class)->getTopByDate($ranking);
        
        if (empty($videos)) {
            $this->logger->error("[setPositions] no videos");
            return;
        }

        $previousRanking = $this->em->getRepository(Ranking::class)->getPreviousRanking($ranking);

        if (!$previousRanking) {
            $this->logger->error("[setPositions] no previousRanking");
            return;
        }

        $this->logger->info("[setPositions] n-1=" . $ranking->getId() . " et n-2=" . $previousRanking->getId());
        $position = 1;        

        foreach ($videos as $video) {
            $videoRankings = $video->getVideoRankings();
            foreach ($videoRankings as $videoRanking) {
                $videoRanking->setPosition($position);
                //recuperer l'ancien classement pour comparer
                if ($previousRanking) {
                    $videoRanking = $this->compareRanking($videoRanking, $previousRanking);
                }
            }
            $position++;
        }

        $this->em->flush();
    }

    /**
     * compareRanking
     *
     * @param VideoRanking $videoRanking
     * @param Ranking $previousRanking
     * @return VideoRanking
     */
    public function compareRanking(VideoRanking $videoRanking, Ranking $previousRanking): VideoRanking
    {
        $videoRankings = $previousRanking->getVideoRankings();
        foreach ($videoRankings as $previousVideoRanking) {
            if ($previousVideoRanking->getVideo()->getId() != $videoRanking->getVideo()->getId()) {
                $this->logger->info("la video n'est pas presente dans le classement precedent");
                return $videoRanking;
            }

            $previousPosition = $previousVideoRanking->getPosition();
            $position = $videoRanking->getPosition();
            if ($position) {
                $icon =  $this->setIcons($previousPosition, $position);
                $videoRanking->setIcon($icon);
            }
        }

        return $videoRanking;
    }

    /**
     * setIcons
     *
     * @param integer $previousPosition
     * @param integer $position
     * @return string
     */
    public function setIcons($previousPosition, $position) :string
    {
        if (!$previousPosition) {
            return 'add';
        }

        $icon = 'forward';

        switch ($position) {
            case $position < $previousPosition:
                $icon = 'up';
            break;
            case $position > $previousPosition:
                $icon = 'down';
            break;
        }

        return $icon;
    }
    
    /**
     * getIcon
     *
     * @param VideoRanking $videoRanking
     * @param Ranking $previousRanking
     * @param integer $position
     * @return string
     */
    public function getIcon(VideoRanking $videoRanking, $previousRanking, $position) :string
    {
        $videoRanking->setPosition($position);
        if ($previousRanking) {
            $videoRanking = $this->compareRanking($videoRanking, $previousRanking);
        }

        if (!$videoRanking->getIcon()) {
            return 'add';
        }

        return $videoRanking->getIcon();
    }

    public function setYoutubeData(Video $video, $idYoutube)
    {
        $this->parser->setDocument("http://gdata.youtube.com/feeds/api/videos/$idYoutube");

        $video->setDescription($this->parser->get('content'));
        $video->setUrl($idYoutube);

        return $video;
    }

    /**
     * incrementVideoRanking
     *
     * @param Video $video
     * @param Ranking $ranking
     * @return void
     */
    public function incrementVideoRanking(Video $video, Ranking $ranking)
    {
        $videoRanking = $this->em->getRepository(VideoRanking::class)
            ->getOne($video, $ranking);

        if ($videoRanking) {
            $nbVu = $videoRanking->getNbVu() + 1;
            $videoRanking->setNbVu($nbVu);
        } else {
            $videoRanking =  $this->createVideoRanking($ranking, $video);
            $this->em->persist($videoRanking);
        }                
       
        $this->em->flush();

        $this->logger->info("[incrementVideoRanking] Increment vidéo", [
            'video id' => $video->getId(),
            'ranking id' => $ranking->getId(),
        ]);
    }

    public function videosTop($nbVideosTop, $nbResult)
    {
        $videos = $this->em
            ->getRepository('SpicySiteBundle:Video')
            ->getFlux($nbVideosTop, true);

        if (empty($videos)) {
            throw new \Exception('Video inexistant', 404);
        }

        $videos = $this->getRandVideos($videos, $nbResult);

        return $videos;
    }

    public function getRandVideos($videos, $nbVideos)
    {
        shuffle($videos);
        $videos = array_slice($videos, 0, $nbVideos);

        return $videos;
    }

    public function getMessage(Video $video, $type = self::NEW_VIDEO)
    {
        $message = 'Nouveau titre sur Mimizik.com : ';
        
        if ($type == self::TOP_VIDEO) {
            $message = 'Vidéo #Top10mimizik >> ';
        }

        $message = $message . $video->getNomArtistes() . ' - ' . $video->getTitre() . '. ';
        $message = $message . $video->getDescription();
        $message = $message . ' #mimizik';

        return $message;
    }

    /**
     * getCleanLink
     *
     * @param string $link
     * @return string
     */
    public function getCleanLink($link)
    {
        $link = str_replace("https", "http", $link);
        $link = str_replace("/app_dev.php/", "/", $link);

        return $link;
    }

    /**
     *
     * @param Artiste $artiste
     * @param type $nbVideos
     * @return type
     * @throws \Exception
     */
    public function getLastVideos(Artiste $artiste, $nbVideos)
    {
        $videos = $this->em
            ->getRepository('SpicySiteBundle:Video')
            ->getLastByArtiste($nbVideos, $artiste);

        if (empty($videos)) {
            throw new \Exception('Videos inexistant', 404);
        }

        return $videos;
    }
}
