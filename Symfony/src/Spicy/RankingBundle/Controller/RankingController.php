<?php

namespace Spicy\RankingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spicy\RankingBundle\Entity\RankingType;

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
            'nombrePage'=>ceil(count($rankings)/ $nbSuggestion),
            'page'=>$page ,
            'rankingType'=>  RankingType::MOIS   
        ));
    }
    
    public function indexYearAction($page)
    {
        $nbSuggestion=$this->container->getParameter('nbSuggestion');

        $rankings=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicyRankingBundle:Ranking')
                ->getRankings($page,$nbSuggestion,  RankingType::ANNEE);
        
        return $this->render('SpicyRankingBundle:Ranking:index.html.twig',array(
            'rankings'=>$rankings,
            'nombrePage'=>ceil(count($rankings)/ $nbSuggestion),
            'page'=>$page,
            'rankingType'=>  RankingType::ANNEE   
        ));
    }
    
    public function showLastAction()
    {
        $em=$this->getDoctrine()->getManager();
        $videoManager = $this->container->get('mimizik.videoService');
        
        $ranking=$videoManager->getRanking(RankingType::MOIS);//last ranking
        
        if(!$ranking)//mauvais id
        {
            throw new \Exception('Classement indisponible');
        }
        $previousRanking=$em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
        
        $videos=$em->getRepository('SpicySiteBundle:Video')->getTopByDate($ranking,3);
        
        
        return $this->render('SpicyRankingBundle:Ranking:showLast.html.twig', array(
            'ranking' => $ranking,
            'previousRanking'=>$previousRanking,
            'videos'=>$videos
        ));
    }
    
    public function showAction($id,$type_id)
    {
        $em=$this->getDoctrine()->getManager();
//        $videoManager = $this->container->get('mimizik.videoService');
        
        //$ranking=$videoManager->getRanking($id);
        $ranking=$em->getRepository('SpicyRankingBundle:Ranking')->getOne($id);
        
        if(!$ranking)//mauvais id
        {
            throw new \Exception('Classement indisponible');
        }
        
        $previousRanking=$em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
        
        $max=10;
        
        if($type_id==RankingType::MOIS)
            $max=10;
        
        if($type_id==RankingType::ANNEE)
            $max=30;
        
        $videos=$em->getRepository('SpicySiteBundle:Video')->getTopByDate($ranking,$max);
        
        return $this->render('SpicyRankingBundle:Ranking:show.html.twig', array(
            'ranking' => $ranking,
            'previousRanking'=>$previousRanking,
            'videos'=>$videos
        ));
    }
}