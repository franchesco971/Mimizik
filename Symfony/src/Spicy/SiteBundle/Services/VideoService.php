<?php

namespace Spicy\SiteBundle\Services;

use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Spicy\SiteBundle\Entity\Video;
use Spicy\RankingBundle\Entity\Ranking;
use Spicy\RankingBundle\Entity\RankingType;
use Spicy\RankingBundle\Entity\VideoRanking;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Services\ParseurXMLYoutube;

class VideoService
{
    protected $em;
    protected $logger;
    protected $parser;
    
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
            $nbVu=0;
            $ranking=$this->getRanking();
            
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
    }
    
    public function getRanking($last=true,$id=0) 
    {
        $lastRanking=$this->getLastRanking();
                
        if(!$last && $id)//un classement en particulier
        {
            $ranking=$this->em->getRepository('SpicyRankingBundle:Ranking')->getOne($id);
            
            if(!$ranking)//mauvais id
            {
                throw new \Exception('Classement indisponible');
            }
        }
        else //le dernier classement
        {
            $ranking=$lastRanking;
        }        
        
        /*** base de donnee vide**/
        if ($lastRanking == null) {
            $this->logger->info("Creation d'un ranking ");
            $this->logger->error("Base de donnee vide last=$last et id=$id");
            $ranking=$this->createRanking();
        }
        else
        {
            $now=new \DateTime("now");
            /*** s'il en faut un nouveau **/
            if($ranking->getEndRanking()<$now && $last)
            {
                /**** fige les positions du classement précédent **/
                $this->setPositions($ranking);      
                /**** crée un nouveau classement ***/
                $this->logger->info("Creation d'un ranking");
                $this->logger->error('Crée un nouveau classement'.$ranking->getEndRanking()->format('Y-m-d H:i:sP').'<'.$now->format('Y-m-d H:i:sP'));
                $ranking=$this->createRanking();
            }
        }
        
        return $ranking;
    }
    
    public function createRanking() 
    {
        $now=new \DateTime("now");        
        
        $ranking=new Ranking();
        $dateRanking=$now;
        $startRanking=new \DateTime("first day of this month");
        $endRanking=new \DateTime("first day of next month");
        $rankingType=$this->em->getRepository('SpicyRankingBundle:RankingType')->find(RankingType::MOIS);

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
        
        $position=1;
        if(!is_null($previousRanking))
        {
            foreach ($videos as $video) 
            {            
                foreach ($video->getVideoRankings() as $videoRanking) 
                {
                    $videoRanking->setPosition($position);
                    //recuperer l'ancien classement pour comparer
                    if($previousRanking!=NULL)
                    {
                        $videoRanking=$this->compareRanking($videoRanking,$previousRanking);
                    }

                    $this->em->persist($videoRanking);  
                }
                $position++;
            }
        }
    }
    
    public function compareRanking(VideoRanking $videoRanking,Ranking $previousRanking) 
    {        
        foreach ($previousRanking->getVideoRankings() as $previousVideoRanking) 
        {
            /** la video est presente dans le classement precedent **/
            if($previousVideoRanking->getVideo()->getId()==$videoRanking->getVideo()->getId())
            {                
                $previousPosition=$previousVideoRanking->getPosition();
                $position=$videoRanking->getPosition();
                if(!is_null($position))
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
    
    public function getLastRanking() 
    {
        $rankings=new \Doctrine\Common\Collections\ArrayCollection();
        $rankings=$this->em->getRepository('SpicyRankingBundle:Ranking')->getByDate();
        
        if(empty($rankings))
        {
            $this->logger->info("empty rankings");
            return NULL;
        }
        else
        {
            return $rankings[0];
        }
    }
    
    public function setYoutubeData(Video $video,$idYoutube) 
    {
        $this->parser->setDocument("http://gdata.youtube.com/feeds/api/videos/$idYoutube");

        $video->setDescription($this->parser->get('content'));
        $video->setUrl($idYoutube);
                
        return $video;
    }
}

