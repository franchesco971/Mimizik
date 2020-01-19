<?php

namespace Spicy\FluxBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Spicy\FluxBundle\Services\Twitter;
use Spicy\ITWBundle\Entity\Interview;
use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Services\VideoService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FluxController extends Controller
{
    public function fluxArtistesAction(EntityManagerInterface $em)
    {
        $artistes = $em->getRepository('SpicySiteBundle:Artiste')->getFlux();

        return $this->render('SpicyFluxBundle:Flux:fluxArtistes.html.twig', array(
            'artistes' => $artistes,
            'selfLink' => $this->generateUrl('spicy_site_flux_artistes')
        ));
    }

    public function fluxIndexAction()
    {
        return $this->render('SpicyFluxBundle:Flux:fluxIndex.html.twig');
    }

    public function getVideos(EntityManagerInterface $em)
    {
        $videos = $em->getRepository(Video::class)->getAvecArtistes(30);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        return $videos;
    }

    public function fluxVideosAction(EntityManagerInterface $em)
    {
        $videos = $this->getVideos($em);

        $response = new Response(
            $this->renderView('SpicyFluxBundle:Flux:fluxVideos.html.twig', array(
                'videos' => $videos,
                'selfLink' => $this->generateUrl('spicy_site_flux_videos'),
                'pubDates' => $this->getTabData($videos)
            )),
            200
        );

        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    public function fluxVideosJsonAction(EntityManagerInterface $em, $page = 1)
    {
        $tabVideos = [];
        $videoRepo =  $em->getRepository(Video::class);

        $videos = $videoRepo->getSuiteJson($page, 10);

        foreach ($videos as $video) {
            if ($video) {
                $tabVideos[] = $video;
            }
        }

        $response = new Response(json_encode((array) $tabVideos), 200);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function fluxVideosTwitterAction(EntityManagerInterface $em, Twitter $twitterService)
    {
        $videos = $em->getRepository('SpicySiteBundle:Video')->getAvecArtistes(30);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        $arrayDescriptions = $twitterService->twitterType($videos);

        return $this->render('SpicyFluxBundle:Flux:videosTwitter.html.twig', array(
            'videos' => $videos,
            'descriptions' => $arrayDescriptions,
            'selfLink' => $this->generateUrl('spicy_site_flux_videos_twitter'),
            'pubDates' =>  $this->getTabData($videos)
        ));
    }

    public function fluxRetroAction(EntityManagerInterface $em)
    {
        $videos = $em->getRepository('SpicySiteBundle:Video')->getAllRetro(50);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        return $this->render('SpicyFluxBundle:Flux:fluxVideos.html.twig', array(
            'videos' => $videos,
            'selfLink' => $this->generateUrl('spicy_site_flux_retro'),
            'pubDates' =>  $this->getTabData($videos)
        ));
    }

    public function fluxRetroTwitterAction(EntityManagerInterface $em, Twitter $twitterService)
    {
        $videos = $em->getRepository('SpicySiteBundle:Video')->getAllRetro(50);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        $arrayDescriptions = $twitterService->twitterType($videos);

        return $this->render('SpicyFluxBundle:Flux:videosTwitter.html.twig', array(
            'videos' => $videos,
            'descriptions' => $arrayDescriptions,
            'selfLink' => $this->generateUrl('spicy_site_flux_retro_twitter'),
            'pubDates' =>  $this->getTabData($videos)
        ));
    }

    public function fluxVideosTopAction(VideoService $videoService)
    {
        $videos = $videoService->videosTop(5, 2);

        return $this->render('SpicyFluxBundle:Flux:fluxVideos.html.twig', array(
            'videos' => $videos,
            'selfLink' => $this->generateUrl('mimizik_flux_videos_top'),
            'pubDates' =>  $this->getTabData($videos, true)
        ));
    }

    public function fluxVideosTopTwitterAction(VideoService $videoService, Twitter $twitterService)
    {
        $videos = $videoService->videosTop(5, 2);
        $arrayDescriptions = $twitterService->twitterType($videos);

        return $this->render('SpicyFluxBundle:Flux:videosTwitter.html.twig', array(
            'videos' => $videos,
            'descriptions' => $arrayDescriptions,
            'selfLink' => $this->generateUrl('mimizik_flux_videos_top_twitter'),
            'pubDates' =>  $this->getTabData($videos, true)
        ));
    }

    public function getTabData($videos, $top = false)
    {
        $datas = [];
        foreach ($videos as $video) {
            if ($top) {
                $pubDate = new \DateTime('NOW');
            } else {
                $pubDate = $video->getDateVideo();
            }
            $datas[$video->getId()] = array('pubDate' => $pubDate);
        }

        return $datas;
    }

    /**
     * 
     * @return type
     */
    public function fluxLyricsAction()
    {
        $lyrics = $this->container->get('mimizik.repository.paroles')->getAll(10);

        if ($lyrics == null) {
            throw $this->createNotFoundException('Paroles inexistant');
        }

        return $this->render('SpicyFluxBundle:Flux:fluxLyrics.html.twig', array(
            'lyrics' => $lyrics,
            'selfLink' => $this->generateUrl('mimizik_flux_lyrics')
        ));
    }

    /**
     * 
     * @return type
     */
    public function fluxITWAction(EntityManagerInterface $em)
    {
        $interviews = $em->getRepository(Interview::class)->getAll(10);

        if ($interviews == null) {
            throw $this->createNotFoundException('ITW inexistant');
        }

        return $this->render('SpicyFluxBundle:Flux:fluxITW.html.twig', array(
            'interviews' => $interviews,
            'selfLink' => $this->generateUrl('spicy_site_artistes')
        ));
    }
}
