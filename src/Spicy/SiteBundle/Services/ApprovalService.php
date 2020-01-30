<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spicy\SiteBundle\Services;

use Spicy\SiteBundle\Entity\Approval;
use Spicy\SiteBundle\Entity\Video;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Spicy\SiteBundle\Entity\TypeVideo;
use Spicy\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;

/**
 * Description of ApprovalService
 *
 * @author franciscopol
 */
class ApprovalService
{
    private $user;

    private $em;

    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->user = $this->getUser($security->getUser());        
    }

    /**
     * getUser
     *
     * @param UserInterface|null $user
     * @return UserInterface|null
     */
    public function getUser($user)
    {
        if (!$user) 
        {
            return $this->em->getRepository(User::class)->findOneBy(['username' => 'franchesco971']);
        }

        return $user;
    }

    /**
     * 
     * @return Approval
     */
    public function getDefaultApproval(Video $video = null)
    {
        $approval = new Approval();
        $video = $video ?? new Video();
        $typeClip = $this->em->getRepository(TypeVideo::class)->findOneBy(['libelle' => 'Clip']);
        $video->addTypeVideo($typeClip)->setEtat(false);
        $approval->setTitle($video)->setUser($this->user);

        return $approval;
    }
}
