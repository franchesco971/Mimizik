<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Spicy\AppBundle\Services;

use Spicy\SiteBundle\Services\VideoService;
use Spicy\AppBundle\Services\FacebookManager;
use Spicy\SiteBundle\Entity\Video;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of PublishService
 *
 * @author franciscopol
 */
class PublishService
{
    private $router;
    
    private $videoservice;
    
    private $facebookManager;
    
    private $em;
    
    private $session;
    
    private $publishDate;


    public function __construct($router, VideoService $videoservice, FacebookManager $facebookManager, EntityManager $em, Session $session)
    {
        $this->router = $router;
        $this->videoservice = $videoservice;
        $this->facebookManager = $facebookManager;
        $this->em = $em;
        $this->session = $session;
        $this->publishDate = new \DateTime('NOW');
    }
    
    /**
     * Post dans les 15 minutes
     * @param Video $video
     */
    public function instantPublish(Video $video)
    {
        $date = new \DateTime('NOW');
        $this->publishDate = $date->modify( '+15 minutes' );
        $link = $this->getVideoLink($video);

        $params = $this->getParams($link, $this->publishDate, $video);

        $this->facebookManager->postFeed($params); 
        $this->session->getFlashBag()->add('info', 'La vidéo sera publié dans 15 min');
        
    }
    
    public function topRamdomPublish()
    {
        $randTopVideos = $this->videoservice->videosTop(10,1);
        if($randTopVideos) {
            foreach ($randTopVideos as $randTopVideo) {
                $publishDate = $this->publishDate->modify('+16 hours');
                $link = $link = $this->getVideoLink($randTopVideo);

                $paramsTop = $this->getParams($link, $publishDate, $randTopVideo);

                if(!$this->willBePublishSoon($randTopVideo)) {
                    $this->facebookManager->postFeed($paramsTop);
                    $randTopVideo->setNextPublishDate($publishDate);
                    break;
                }                
            }

            $this->em->flush();
        }
    }
    
    public function getVideoLink(Video $video)
    {
        $link = $this->router->generate('spicy_site_video_slug', array('id' => $video->getId(), 'slug' => $video->getSlug()), true);
        return $this->videoservice->getCleanLink($link);
    }
    
    public function getParams($link, $publishDate, Video $video, $type=self::NEW_VIDEO)
    {
        $paramsTop=[
            'message' => $this->videoservice->getMessage($video,$type),
            'published' => false,
            'scheduled_publish_time' => $publishDate->getTimestamp(),
            'link' => $link
        ];
        
        return $paramsTop;
    }
    
    public function willBePublishSoon(Video $video)
    {
        $result = false;
        $nextPublishDate = $video->getNextPublishDate();        
        $dateDemain = new \DateTime('TOMORROW');
        
        if($nextPublishDate) {
            //conversion de la date pour comparaison
            $nextPublishDate = $nextPublishDate->format('Y-m-d');
            $nextPublishDate = new \DateTime($nextPublishDate);
            
            if($nextPublishDate == $dateDemain) {
                $result = true;
            }            
        }
        
        return $result;
    }
}
