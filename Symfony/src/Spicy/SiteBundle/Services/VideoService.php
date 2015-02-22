<?php

namespace Spicy\SiteBundle\Services;

use Doctrine\ORM\EntityManager;
use Spicy\SiteBundle\Entity\Video;
use Spicy\RankingBundle\Entity\Ranking;
use Spicy\RankingBundle\Entity\RankingType;
use Spicy\RankingBundle\Entity\VideoRanking;

class VideoService
{
    protected $em;
    
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }
    
    public function increment(Video $video) 
    {
        $ipInterdites=array('46.218.242.177','82.229.222.236','127.0.0.1');
        
        if(!in_array($_SERVER['REMOTE_ADDR'], $ipInterdites))            
        {
            $nbVu=0;
            $ranking=$this->getRanking();
            
            $videoRanking = $this->em->getRepository('SpicyRankingBundle:VideoRanking')
                    ->getOne($video,$ranking);

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
    
    public function getRanking() 
    {     
        
        $ranking=$this->em->getRepository('SpicyRankingBundle:Ranking')->getLastRanking();
        
        /*** base de donnee vide**/
        if ($ranking == null) {
            $ranking=$this->createRanking();
        }
        else
        {
            $now=new \DateTime("now");
            
            /*** s'il en faut un nouveau **/
            if($ranking->getEndRanking()<$now)
            {
                /**** fige les positions du classement précédent **/
                $this->setPositions($ranking);
                /**** crée un nouveau classement ***/
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
        $videos=$this->em->getRepository('SpicySiteBundle:Video')->getTop10byMonth($ranking);
        $previousRanking=$em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
        
        $position=1;
        foreach ($videos as $video) 
        {            
            foreach ($video->getVideoRankings() as $videoRanking) 
            {
                $videoRanking->setPosition($position);
                //recuperer l'ancien classsment pour comparer
                if($previousRanking!=NULL)
                {
                    $videoRanking=$this->compareRanking($videoRanking,$previousRanking);
                }
                
                $this->em->persist($videoRanking);  
            }
            $position++;
        }
    }
    
    public function compareRanking(VideoRanking $videoRanking,Ranking $previousRanking) 
    {        
        foreach ($previousRanking->getVideoRankings() as $previousVideoRanking) 
        {
            if($previousVideoRanking->getVideo()->getId()==$videoRanking->getId())
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
                case $position>$previousPosition:
                    $icon='up';
                    break;
                case $position<$previousPosition:
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
}

