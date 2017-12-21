<?php

namespace Spicy\SiteBundle\Services;

use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Entity\Artiste;
use Spicy\RankingBundle\Entity\Ranking;
use Spicy\RankingBundle\Entity\RankingType;
use Spicy\RankingBundle\Entity\VideoRanking;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Services\ParseurXMLYoutube;
use Doctrine\Common\Collections\ArrayCollection;

class VideoService
{
    protected $em;
    protected $logger;
    protected $parser;
    
    const TOP_VIDEO=1;
    const NEW_VIDEO=2;

    public function __construct(EntityManager $entityManager, Logger $logger,ParseurXMLYoutube $parser)
    {
        $this->em = $entityManager;
        $this->logger=$logger;
        $this->parser=$parser;
    }
    
    public function increment(Video $video) 
    {
        $ipInterdites=array('46.218.242.177','82.229.222.236','127.0.0.1');
        //$ipInterdites=array();
        $valid=true;
        foreach ($video->getGenreMusicaux() as $genre) {
            $idGenre=$genre->getId();
            if($idGenre==GenreMusical::RETRO)
            {
                $valid=false;
            }
        }
        
        if(!in_array($_SERVER['REMOTE_ADDR'], $ipInterdites) && $valid)            
        {
            $nbVu = 0;
            $ranking = $this->getRanking(RankingType::MOIS);
            $yearRanking = $this->getRanking(RankingType::ANNEE);
            
            $this->incrementVideoRanking($video,$ranking);
            $this->incrementVideoRanking($video,$yearRanking);
        }
    }
    
    public function getRanking($type = RankingType::MOIS) 
    {
        $now=new \DateTime("now");
        $ranking=$this->em->getRepository('SpicyRankingBundle:Ranking')->getByDate($type);                
        
        /*** base de donnee vide**/
        if ($ranking == null) {
                  
            /**** crée un nouveau classement ***/
            $this->logger->info("Creation d'un ranking de type $type ");            
            $ranking=$this->createRanking($type);
            $this->logger->error('Crée un nouveau classement '.$ranking->getStartRanking()->format('Y-m-d H:i:sP').'/ '.$ranking->getEndRanking()->format('Y-m-d H:i:sP').' à '.$now->format('Y-m-d H:i:sP'));
            
            $previousRanking=$this->em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
            
            /**** fige les positions du classement précédent **/
            if($previousRanking)
            {
                $this->setPositions($previousRanking);
            }
            else
            {
                $this->logger->error("(select max(ra.id) from SpicyRankingBundle:Ranking ra "
                        . "where ra.id<".$ranking->getId()." AND ra.rankingType=".$ranking->getRankingType()->getId().")");
            }
        }
        
        return $ranking;
    }
    
    public function createRanking($type) 
    {
        $now=new \DateTime("now");        
        
        $ranking=new Ranking();
        $dateRanking=$now;
        if($type==RankingType::MOIS)
        {
            $startRanking=new \DateTime("first day of this month");
            $endRanking=new \DateTime("first day of next month");
        }
        elseif ($type==RankingType::ANNEE) 
        {
            $startRanking=new \DateTime("first day of this year");
            $endRanking=new \DateTime("first day of next year");
        }
        
        $startRanking->setTime(0, 0, 1);        
        $endRanking->setTime(0, 0, 0);
        $rankingType=$this->em->getRepository('SpicyRankingBundle:RankingType')->find($type);

        $ranking->setDateRanking($dateRanking);
        $ranking->setStartRanking($startRanking);
        $ranking->setEndRanking($endRanking);
        $ranking->setRankingType($rankingType);

        $this->em->persist($ranking);
        $this->em->flush();
        
        return $ranking;
    }
    
    public function createVideoRanking($ranking,$video) 
    {
        $videoRanking=new VideoRanking();
        $videoRanking->setRanking($ranking);
        $videoRanking->setVideo($video);
        $videoRanking->setNbVu(1);
        
        return $videoRanking;
    }
    
    public function setPositions(Ranking $ranking) 
    {        
        $videos=$this->em->getRepository('SpicySiteBundle:Video')->getTopByMonth($ranking);        
        $previousRanking=$this->em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
        $this->logger->info("setPositions n-1=".$ranking->getId()." et n-2=".$previousRanking->getId());
        $position=1;
        
        if($previousRanking)
        {
            foreach ($videos as $video) 
            {            
                foreach ($video->getVideoRankings() as $videoRanking) 
                {
                    $videoRanking->setPosition($position);
                    //recuperer l'ancien classement pour comparer
                    if($previousRanking)
                    {                        
                        $videoRanking=$this->compareRanking($videoRanking,$previousRanking);
                    } 
                }
                $position++;
            }
            
            $this->em->flush();
        }
    }
    
    public function compareRanking(VideoRanking $videoRanking,Ranking $previousRanking) 
    {        
        foreach ($previousRanking->getVideoRankings() as $previousVideoRanking) 
        {
            /** la video est presente dans le classement precedent **/
            if($previousVideoRanking->getVideo()->getId()==$videoRanking->getVideo()->getId())
            {                
                $this->logger->info("la video est presente dans le classement precedent");
                $previousPosition=$previousVideoRanking->getPosition();
                $position=$videoRanking->getPosition();
                if($position)
                {
                    $icon=  $this->setIcons($previousPosition, $position);
                    
                    $videoRanking->setIcon($icon);
                }
            }
        }
        
        return $videoRanking;
    }
    
    public function setIcons($previousPosition,$position)
    {
        if(!is_null($previousPosition))
        {
            switch ($position) {
                case $position<$previousPosition:
                    $icon='up';
                    break;
                case $position>$previousPosition:
                    $icon='down';
                    break;
                case $position==$previousPosition:
                    $icon='forward';
                    break;
            }
        }
        else
        {
            $icon='add';
        }
        
        return $icon;
    }
    
    public function getIcon(VideoRanking $videoRanking,$previousRanking,$position) 
    {
        $videoRanking->setPosition($position);
        if(!is_null($previousRanking))
        {            
            $videoRanking=$this->compareRanking($videoRanking, $previousRanking);
        }
        
        $icon='add';
        if(!is_null($videoRanking->getIcon()))
        {            
            $icon=$videoRanking->getIcon();
        }
        
        return $icon;
    }
    
    public function setYoutubeData(Video $video,$idYoutube) 
    {
        $this->parser->setDocument("http://gdata.youtube.com/feeds/api/videos/$idYoutube");

        $video->setDescription($this->parser->get('content'));
        $video->setUrl($idYoutube);
                
        return $video;
    }
    
    public function incrementVideoRanking($video,$ranking) 
    {
        $videoRanking = $this->em->getRepository('SpicyRankingBundle:VideoRanking')
                    ->getOne($video,$ranking);
            
        /** base vide ***/
        if ($videoRanking == null) 
        {
            $videoRanking=  $this->createVideoRanking($ranking,$video);
        }
        else
        {
            $nbVu=$videoRanking->getNbVu()+1;
            $videoRanking->setNbVu($nbVu);
        }

        $this->em->persist($videoRanking);
        $this->em->flush();
    }
    
    public function videosTop($nbVideosTop,$nbResult)
    {        
        $videos=$this->em
                ->getRepository('SpicySiteBundle:Video')
                ->getFlux($nbVideosTop,true);

        if (empty($videos)) {
            throw new \Exception ('Video inexistant', 404);
        }
        
        $videos=$this->getRandVideos($videos, $nbResult);
        
        return $videos;
    }
    
    public function getRandVideos($videos, $nbVideos) {
        shuffle($videos);
        $videos = array_slice($videos, 0, $nbVideos);
        return $videos;
    }
    
    public function getMessage(Video $video,$type=self::NEW_VIDEO) 
    {
        if($type==self::TOP_VIDEO)
        {
            $message='Vidéo #Top10mimizik >> ';
        }
        else {
            $message='Nouveau titre sur Mimizik.com : ';
        }        
        
        $message=$message.$video->getNomArtistes().' - '.$video->getTitre().'. ';
        $message=$message.$video->getDescription();
        $message=$message.' #mimizik';
        
        return $message;
    }
    
    public function getCleanLink($link) {
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
    public function getLastVideos(Artiste $artiste, $nbVideos) { //TODO déplacer dans service 
        $videos = $this->em
                ->getRepository('SpicySiteBundle:Video')
                ->getLastByArtiste($nbVideos,$artiste);

        if (empty($videos)) {
            throw new \Exception ('Videos inexistant', 404);
        }
        
        return $videos;
    }
}

