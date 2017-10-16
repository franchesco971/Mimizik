<?php

namespace Mimizik\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spicy\SiteBundle\Entity\Artiste;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Description of ArticleController
 *
 * @author franchesco971
 */
class ArticleController extends FOSRestController {
    
    /**
     * 
     * @param Artiste $artiste
     * @return Artiste
     * 
     * @Rest\Get(
     *     path = "/artistes/{id}",
     *     name = "api_mimizik_artiste_show",
     *     requirements = {"id"="\d+"}
     * )
     * @Rest\View(StatusCode = 200)
     */
    public function showAction(Artiste $artiste) {
        return $artiste;
    }
    
    
}
