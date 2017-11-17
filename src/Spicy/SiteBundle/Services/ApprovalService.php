<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spicy\SiteBundle\Services;

use Spicy\SiteBundle\Entity\Approval;
use Spicy\SiteBundle\Entity\Video;

/**
 * Description of ApprovalService
 *
 * @author franciscopol
 */
class ApprovalService
{
    private $user;
    
    private $typeVideoRepository;
    
    public function __construct($tokenStorage,$typeVideoRepository )
    {
        $this->typeVideoRepository = $typeVideoRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }
    
    /**
     * 
     * @return Approval
     */
    public function getDefaultApproval(Video $video=null)
    {
        $approval = new Approval();
        $video = ($video) ? $video : new Video();
        $typeClip = $this->typeVideoRepository->findOneBy(['libelle' => 'Clip']);
        $video->addTypeVideo($typeClip)->setEtat(false);
        $approval->setTitle($video)->setUser($this->user);
        
        return $approval;
    }
}
