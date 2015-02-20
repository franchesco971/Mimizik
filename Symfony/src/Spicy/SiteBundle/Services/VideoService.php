<?php

namespace Spicy\SiteBundle\Services;

use Doctrine\ORM\EntityManager;
use Spicy\SiteBundle\Entity\Video;

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
            $nbVu=$video->getNbVu()+1;
            $video->setNbVu($nbVu);

            $this->em->persist($video);
            $this->em->flush();
        }
    }
}

