<?php

namespace Spicy\RankingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RankingController extends Controller
{
    public function showAction()
    {
        $em=$this->getDoctrine()->getManager();
        
        $ranking=$em->getRepository('SpicyRankingBundle:Ranking')->getLastRanking();
        
        //var_dump($ranking->getVideoRanking());
        //exit;
        
        return $this->render('SpicyRankingBundle:Ranking:show.html.twig', array(
            'ranking' => $ranking
        ));
    }
}