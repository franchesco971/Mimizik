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
        $nbVu=$video->getNbVu()+1;
        $video->setNbVu($nbVu);
        
        $this->em->persist($video);
        $this->em->flush();
    }
}

