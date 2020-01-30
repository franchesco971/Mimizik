<?php

namespace Spicy\RankingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spicy\RankingBundle\Entity\RankingType;
use Spicy\SiteBundle\Services\VideoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RankingController extends Controller
{
    private $nbSuggestion;

    public function __construct($nbSuggestion)
    {
        $this->nbSuggestion = $nbSuggestion;
    }

    /**
     * 
     * @param type $page
     * @return type
     * @throws BadRequestHttpException
     */
    public function indexAction($page)
    {
        if ($page == '__id__') {
            throw new BadRequestHttpException("Ressource introuvable");
        }

        $rankings = $this->getDoctrine()
            ->getManager()
            ->getRepository('SpicyRankingBundle:Ranking')
            ->getRankings($page, $this->nbSuggestion);

        return $this->render('SpicyRankingBundle:Ranking:index.html.twig', array(
            'rankings' => $rankings,
            'nombrePage' => ceil(count($rankings) / $this->nbSuggestion),
            'page' => $page,
            'rankingType' => RankingType::MOIS
        ));
    }
    
    public function indexYearAction($page)
    {
        $rankings = $this->getDoctrine()
            ->getManager()
            ->getRepository('SpicyRankingBundle:Ranking')
            ->getRankings($page, $this->nbSuggestion,  RankingType::ANNEE);

        return $this->render('SpicyRankingBundle:Ranking:index.html.twig', array(
            'rankings' => $rankings,
            'nombrePage' => ceil(count($rankings) / $this->nbSuggestion),
            'page' => $page,
            'rankingType' =>  RankingType::ANNEE
        ));
    }

    public function showLastAction(EntityManagerInterface $em, VideoService $videoManager)
    {
        $ranking = $videoManager->getRanking(RankingType::MOIS); //last ranking

        $previousRanking = $em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);

        $videos = $em->getRepository('SpicySiteBundle:Video')->getTopByDate($ranking, 3);


        return $this->render('SpicyRankingBundle:Ranking:showLast.html.twig', array(
            'ranking' => $ranking,
            'previousRanking' => $previousRanking,
            'videos' => $videos
        ));
    }

    public function showAction(EntityManagerInterface $em, $id, $type_id)
    {
        $ranking = $em->getRepository('SpicyRankingBundle:Ranking')->getOne($id);

        if (!$ranking) //mauvais id
        {
            throw new \Exception('Classement indisponible');
        }

        $previousRanking = $em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);

        $max = 10;

        if ($type_id == RankingType::MOIS)
            $max = 10;

        if ($type_id == RankingType::ANNEE)
            $max = 30;

        $videos = $em->getRepository('SpicySiteBundle:Video')->getTopByDate($ranking, $max);

        return $this->render('SpicyRankingBundle:Ranking:show.html.twig', array(
            'ranking' => $ranking,
            'previousRanking' => $previousRanking,
            'videos' => $videos
        ));
    }
    
    public function ajaxRankingSideAction(EntityManagerInterface $em, $type)
    {
        $rankings = $em->getRepository('SpicyRankingBundle:Ranking')->getRankingsByType(5, $type);

        return $this->render('SpicyRankingBundle:Ranking:_side.html.twig', array(
            'rankings' => $rankings,
            'rankingType' => $type
        ));
    }
}
