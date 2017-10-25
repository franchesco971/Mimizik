<?php

namespace Mimizik\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Spicy\SiteBundle\Entity\GenreMusical;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Description of ArticleController
 *
 * @author franchesco971
 */
class GenreController extends FOSRestController {
    
    /**
     * 
     * @param GenreMusical $genre
     * @return GenreMusical
     * 
     * @Rest\Get(
     *     path = "/genres/{id}",
     *     name = "api_mimizik_genre_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function showAction(GenreMusical $genre) {
        return $genre;
    }
    
    /**
     * @Rest\Post(
     *    path = "/genres",
     *    name = "api_mimizik_genre_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("genre", converter="fos_rest.request_body")
     */
    public function createAction(GenreMusical $genre)
    {
        $em = $this->getDoctrine()->getManager();

//        $em->persist($artiste);

//        $em->flush(); 
        
//        dump($genre);die;
        $genre->setDateGenreMusical(new \DateTime);

        return $this->view($genre, Response::HTTP_CREATED, 
                ['Location' => $this->generateUrl('api_mimizik_genre_show', ['id' => $genre->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }
    
    /**
     * 
     * @return type
     * 
     * @Rest\Get(
     *     path = "/genres",
     *     name = "api_mimizik_genres_list"
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function getGenresAction() 
    {        
        $manager=$this->getDoctrine()->getManager();
        $genres = $manager->getRepository('SpicySiteBundle:GenreMusical')->findAll();
        return $genres;
    }
}
