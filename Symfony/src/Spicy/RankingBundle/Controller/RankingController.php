<?php

namespace Spicy\RankingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RankingController extends Controller
{
    public function showAction($name)
    {
        
        return $this->render('SpicyRankingBundle:Default:index.html.twig', array('name' => $name));
    }
}