<?php

namespace Mimizik\APIBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Spicy\SiteBundle\Entity\Video;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class VideoController extends FOSRestController
{
    /**
     * 
     * @return type
     * 
     * @Rest\Get(
     *     path = "/videos",
     *     name = "api_mimizik_videos_list"
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function getVideosAction()
    {
        $manager=$this->getDoctrine()->getManager();
        $videos = $manager->getRepository(get_class(GenreMusical))->findAll();
        return $videos;
    }
    
    /**
     * 
     * @param Video $video
     * @return Video
     * 
     * @Rest\Get(
     *     path = "/videos/{id}",
     *     name = "api_mimizik_video_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function getVideoAction(Video $video) {
        return $video;
    }
}
