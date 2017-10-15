<?php

namespace Mimizik\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spicy\SiteBundle\Entity\Artiste;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of ArticleController
 *
 * @author franchesco971
 */
class ArticleController extends Controller {
    
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
     * @View()
     */
    public function showAction(Artiste $artiste) {
        return $artiste;
    }
}
