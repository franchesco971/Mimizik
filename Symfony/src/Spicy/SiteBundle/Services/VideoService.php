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
            
        if ($ranking == null) {
            $ranking=$this->createRanking();
        }
        else
        {
            $now=new \DateTime("now");
            
            if($ranking->getEndRanking()<$now)
            {
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
}

