<?php

namespace Spicy\RankingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RankingController extends Controller
{
    public function indexAction($page)
    {
        $nbSuggestion=$this->container->getParameter('nbSuggestion');

        $rankings=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicyRankingBundle:Ranking')
                ->getRankings($page,$nbSuggestion);
        
        return $this->render('SpicyRankingBundle:Ranking:index.html.twig',array(
            'rankings'=>$rankings,
            'nombrePage'=>ceil((count($rankings))/ $nbSuggestion),
            'page'=>$page    
        ));
    }
    
    public function showLastAction()
    {
        $em=$this->getDoctrine()->getManager();
        $videoManager = $this->container->get('mimizik.videoService');
        
        $ranking=$videoManager->getRanking(true);
        $previousRanking=$em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
        
        $videos=$em->getRepository('SpicySiteBundle:Video')->getTopByMonth($ranking,3);
        
        
        return $this->render('SpicyRankingBundle:Ranking:showLast.html.twig', array(
            'ranking' => $ranking,
            'previousRanking'=>$previousRanking,
            'videos'=>$videos
        ));
    }
    
    public function showAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $videoManager = $this->container->get('mimizik.videoService');
        
        $ranking=$videoManager->getRanking(false,$id);
        $now=new \DateTime("now");
        $previousRanking=$em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
        
        $videos=$em->getRepository('SpicySiteBundle:Video')->getTopByMonth($ranking);
        //var_dump($ranking->getEndRanking());
        //var_dump($now);
        //exit;
        return $this->render('SpicyRankingBundle:Ranking:show.html.twig', array(
            'ranking' => $ranking,
            'previousRanking'=>$previousRanking,
            'videos'=>$videos
        ));
    }
}