<?php

namespace Spicy\AppBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Facebook\Facebook;
use Spicy\SiteBundle\Entity\Video;

class FacebookManager
{
    private $container;
    
    public function __construct(Container $container) {
        $this->container=$container;
    }
    
    public function getFacebookObject() {
        $fb = new Facebook([
                'app_id'                => $this->container->getParameter('app_fb_id'),
                'app_secret'            => $this->container->getParameter('app_fb_secret'),
                'default_graph_version' => $this->container->getParameter('app_fb_graph_version'),
              ]);
        
        return $fb;
    }
    
    public function getMessage(Video $video) 
    {
        $message='Nouveau titre sur Mimizik.com : ';
        $message=$message.$video->getNomArtistes().' - '.$video->getTitre().'. ';
        $message=$message.$video->getDescription();
        $message=$message.' #mimizik';
        
        return $message;
    }
}

