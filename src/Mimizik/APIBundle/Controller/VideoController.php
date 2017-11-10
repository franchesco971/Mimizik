<?php

namespace Mimizik\APIBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Spicy\SiteBundle\Entity\Video;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Mimizik\APIBundle\Representation\Videos;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * 
 */
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
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination page"
     * )
     * @Method({"GET", "OPTIONS"})
     */
    public function getVideosAction($page)
    {
        $manager=$this->getDoctrine()->getManager();
        $pager = $manager->getRepository('SpicySiteBundle:Video')->getAllByPage($this->container->getParameter('nbApiVideos'),(int)$page);
        
        return new Videos($pager);
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
