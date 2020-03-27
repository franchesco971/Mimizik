<?php

namespace Spicy\SiteBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Entity\Artiste;
use Spicy\ITWBundle\Entity\Interview;
use Symfony\Component\HttpFoundation\Request;
use Spicy\LyricsBundle\Entity\Paragraph;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Spicy\SiteBundle\Services\VideoService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Spicy\LyricsBundle\Entity\Lyrics;
use Spicy\RankingBundle\Entity\VideoRanking;
use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Services\Social;
use Spicy\SiteBundle\Services\Tools;

class SiteController extends Controller
{
    public function indexAction($page, EntityManagerInterface $em)
    {
        $videos = $em
            ->getRepository('SpicySiteBundle:Video')
            ->getAvecArtistes($this->container->getParameter('nbMainVideo'), true);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        return $this->render('SpicySiteBundle:Site:index.html.twig', array(
            'videos' => $videos,
            'page' => $page
        ));
    }

    public function indexSuiteAction($page = 1, $topVideos, Tools $toolsManager, EntityManagerInterface $em)
    {
        $nbResultVideoIndex = $this->container->getParameter('nbResultVideoIndex');
        $nbMainVideo = $this->container->getParameter('nbMainVideo');

        $videoIdsList = $toolsManager->getListId($topVideos);

        /*$videos=$em->getRepository('SpicySiteBundle:Video')
                ->getSuiteAvecArtistes(
                        $page,
                        $nbMainVideo,
                        $nbResultVideoIndex,
                        $videoIdsList
                );*/

        $videos = $em->getRepository(Video::class)->getSuite($page, $nbResultVideoIndex, $videoIdsList);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        return $this->render('SpicySiteBundle:Site:indexSuite.html.twig', array(
            'autresVideos' => $videos,
            'nombrePage' => ceil((count($videos) - $nbMainVideo) / $nbResultVideoIndex),
            'page' => $page
        ));
    }

    public function indexSuiteAjaxAction($page = 1, EntityManagerInterface $em, Tools $toolsManager)
    {
        $nbResultVideoIndex = $this->container->getParameter('nbResultVideoIndex');
        $nbMainVideo = $this->container->getParameter('nbMainVideo');
        $topVideos = $em->getRepository(Video::class)->getAvecArtistes($nbMainVideo, true);

        $videoIdsList = $toolsManager->getListId($topVideos);

        $videos = $em->getRepository(Video::class)->getSuite($page, $nbResultVideoIndex, $videoIdsList);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        return $this->render('SpicySiteBundle:Site:indexSuiteAjax.html.twig', array(
            'autresVideos' => $videos,
            'nombrePage' => ceil((count($videos) - $nbMainVideo) / $nbResultVideoIndex),
            'page' => $page
        ));
    }

    /**
     * Undocumented function
     *
     * @param VideoService $videoManager
     * @param Tools $toolsManager
     * @param EntityManagerInterface $em
     * @param Social $socialService
     * @param int $id
     * @param string $referrer
     * @return Response
     */
    public function showAction(VideoService $videoManager, Tools $toolsManager, EntityManagerInterface $em, Social $socialService, LoggerInterface $logger, $id, $referrer = 'video')
    {
        $videoRepo = $em->getRepository(Video::class);
        $video = $videoRepo->getOneAvecArtistes($id);

        if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        $genres = $video->getGenreMusicaux();
        $genreIdsList = $toolsManager->getListId($genres);

        $suggestions = $videoRepo->getSuggestions($genreIdsList, $video->getId());

        $tags = $socialService->getHashtags($video);
        $videoManager->increment($video);

        $nbVuTotal = $em->getRepository(VideoRanking::class)->getCountForVideo($video);
        
        if ($nbVuTotal == null) {
            $logger->warning("No count for video", ["id" => $video->getId()]);
            throw new \Exception("No count for video");
        }

        $currentVideoRanking = $em->getRepository(VideoRanking::class)->getOneOfLastRanking($video);

        if ($currentVideoRanking == null) {
            $logger->warning("No last ranking", ["id" => $video->getId()]);
            throw new \Exception("No last ranking");
        }

        $paragraphType = [Paragraph::INTRO => 'Intro', Paragraph::COUPLET => 'Couplet',  Paragraph::REFRAIN => 'Refrain',  Paragraph::OUTRO => 'Outro'];

        return $this->render('SpicySiteBundle:Site:show.html.twig', array(
            'lavideo' => $video,
            'suggestions' => $suggestions,
            'tags' => $tags,
            'nbVuTotal' => $nbVuTotal['total'],
            'lyrics' => $em->getRepository(Lyrics::class)->getOrdered($video),
            'paragraphType' => $paragraphType,
            'referrer' => $referrer,
            'nbVuMonth' => $currentVideoRanking ? $currentVideoRanking->getNbVu() : 0
        ));
    }

    public function indexArtisteAction(EntityManagerInterface $em, $page)
    {
        $nbArtisteAffiche = $this->container->getParameter('nbArtisteAffiche');

        $artistes = $em
            ->getRepository(Artiste::class)
            ->getAll($page, $nbArtisteAffiche);

        if ($artistes == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }

        $selectArtistes = $em->getRepository(Artiste::class)->findAll();

        return $this->render('SpicySiteBundle:Site:indexArtiste.html.twig', array(
            'artistes' => $artistes,
            'nombrePage' => ceil((count($artistes)) / $nbArtisteAffiche),
            'page' => $page,
            'selectArtistes' => $selectArtistes
        ));
    }

    /**
     * show Artiste
     *
     * @param EntityManagerInterface $em
     * @param Tools $toolsService
     * @param Social $socialService
     * @param integer $id
     * @param integer $page
     * @return Response
     */
    public function showArtisteAction(EntityManagerInterface $em, Tools $toolsService, Social $socialService, $id, $page = 1)
    {
        if ($page == '__id__' || $page == -1) {
            $page = 1;
        }

        $artiste = $em->getRepository(Artiste::class)->getWithTags($id);

        if ($artiste == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }

        $videos = $em->getRepository(Video::class)->getByArtiste($id);

        $nbVideos = count($videos);
        $tabVideos = array_slice($videos, 0, 10);

        // Si la page est supérieur à 1
        if ($page > 1) {
            $nbVids = ($page * 10) - 10;
            $tabVideos = array_slice($videos, $nbVids, 10);
        }

        // TODO mettre suggestions dans service        
        $tabGenres = $toolsService->getAllGenresforVideoCol($videos);
        $tabGenresId = $toolsService->getAllGenresforVideoCol($videos, true);

        $suggestions = $em->getRepository(Video::class)
            ->getSuggestionsArtistes($tabGenresId);

        $tabArtistes = $toolsService->getArtistesBySuggestions($suggestions, $id);

        $fbLink = $socialService->getFacebookLink($artiste);
        $instaLink = $socialService->getInstagramLink($artiste);
        $twitterLinks = $socialService->getArrayTwitterLink($artiste);

        return $this->render('SpicySiteBundle:Site:showArtiste.html.twig', [
            'fbLink' => $fbLink,
            'twitterLinks' => $twitterLinks,
            'instaLink' => $instaLink,
            'videos' => $tabVideos,
            'artiste' => $artiste,
            'genres' => $tabGenres,
            'suggestions' => $tabArtistes,
            'nbVideos' => $nbVideos,
            'page' => $page,
            'profilPic' => $toolsService->getProfilPic($artiste)
        ]);
    }

    public function indexGenreAction($page)
    {
        $nbGenreAffiche = $this->container->getParameter('nbGenreAffiche');

        $genres = $this->getDoctrine()
            ->getManager()
            ->getRepository('SpicySiteBundle:GenreMusical')
            ->getAll($page, $nbGenreAffiche);

        if ($genres == null) {
            throw $this->createNotFoundException('Genre inexistant');
        }

        return $this->render('SpicySiteBundle:Site:indexGenre.html.twig', array(
            'genres' => $genres,
            'nombrePage' => ceil((count($genres)) / $nbGenreAffiche),
            'page' => $page
        ));
    }

    /**
     * @ParamConverter("genre", class="SpicySiteBundle:GenreMusical")
     */
    public function showGenreAction(GenreMusical $genre, $page)
    {        
        if ($page == '__id__') {
            return $this->redirect($this->generateUrl('spicy_site_genre', array('id' => $genre->getId(), 'page' => 1, 'slug' => $genre->getSlug())));
        }

        $nbSuggestion = $this->container->getParameter('nbVideosGenreAffiche');

        $videos = $this->getDoctrine()
            ->getManager()
            ->getRepository('SpicySiteBundle:Video')
            ->getByGenre($genre->getId(), $nbSuggestion, $page);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        return $this->render('SpicySiteBundle:Site:showGenre.html.twig', array(
            'videos' => $videos,
            'genre' => $genre,
            'nombrePage' => ceil((count($videos)) / $nbSuggestion),
            'page' => $page
        ));
    }

    // public function newsAction(ParseurXML $parseur)
    // {
    //     $news = $parseur->parsage();

    //     return $this->render('SpicySiteBundle:Site:news.html.twig', array(
    //         'news' => $news
    //     ));
    // }
    
    public function creditsAction()
    {
        return $this->render('SpicySiteBundle:Site:credits.html.twig');
    }
    
    public function contactAction()
    {
        return $this->render('SpicySiteBundle:Site:contact.html.twig');
    }
    
    public function contactApprovalAction()
    {
        return $this->render('SpicySiteBundle:Site:Contact\contactApproval.html.twig');
    }
    
    // public function testAction()
    // {
    //     $test=[];
    //     $em=$this->getDoctrine()->getManager();
    //     /*$videoManager = $this->container->get('mimizik.videoService');
    //     $randTopVideos=$videoManager->videosTop(10,10);
    //     $dateDemain=new \DateTime('TOMORROW');
    //     var_dump($dateDemain);
    //     foreach ($randTopVideos as $randTopVideo) {
    //         $date=$randTopVideo->getNextPublishDate();
            
    //         if($date)
    //         {
    //             var_dump($date);
    //             $date=$date->format('Y-m-d');
    //             var_dump('$date');
    //             $date=new \DateTime($date);
    //             if($date==$dateDemain)
    //             {
                    
    //             }    
    //             var_dump($date->modify('+16 hours'));
    //             var_dump('**');
    //         }
    //     }
    //     $test=$randTopVideo;*/
    //     $ranking=$em->getRepository('SpicyRankingBundle:Ranking')->getLastRanking();
        
    //     $previousRanking=$em->getRepository('SpicyRankingBundle:Ranking')->getPreviousRanking($ranking);
    //     // var_dump($ranking);
    //     // var_dump($previousRanking);
        
    //     return $this->render('SpicySiteBundle:Site:test.html.twig',array(
    //         'test'=>$test
                
    //     ));
    // }        
    
    public function retroAction()
    {
        $video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getRetro();
        
        // if ($video == null) {
        //     throw $this->createNotFoundException('Video inexistant');
        // }
        
        return $this->render('SpicySiteBundle:Site:retro.html.twig',array(
            'video'=>$video
                
        ));
    }

    public function listArtisteAction(Request $request)
    {
        $page = 1;
        $artistes = [];

        if ($request->isXmlHttpRequest()) {
            $page = $request->request->get("page");

            $nbArtisteAffiche = $this->container->getParameter('nbArtisteAffiche');

            $artistes = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->getAll($page, $nbArtisteAffiche);

            if ($artistes == null) {
                throw $this->createNotFoundException('Artiste inexistant');
            }
        } 

        return $this->render('SpicySiteBundle:Site:listArtiste.html.twig', array(
            'artistes' => $artistes
        ));
    }

    public function listAlphaAction(Request $request)
    {
        $artistes = [];

        if ($request->isXmlHttpRequest()) {
            $lettre = $request->request->get("lettre");

            $artistes = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->getAlpha($lettre);
        }
        
        return $this->render('SpicySiteBundle:Site:listArtiste.html.twig', array(
            'artistes' => $artistes
        ));
    }
    
    public function alphabetAction()
    {        
        return $this->render('SpicySiteBundle:Site:alphabet.html.twig');
    }
    
    // public function TopMoisAction()
    // {
    //     $row = 1;
    //     $tab=array();
        
    //     if (($handle = fopen("csv/test4.csv", "r")) !== FALSE) 
    //     {
    //         while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    //         {
    //             $num = count($data);
                
    //             for ($c=0; $c < $num; $c++) 
    //             {   
    //                 if(preg_match('/^\/video/', $data[$c]))
    //                 {
    //                     list($rien,$debut,$id,$reste)=  explode('/',$data[$c] );
    //                     $video=$this->getDoctrine()
    //                         ->getManager()
    //                         ->getRepository('SpicySiteBundle:Video')
    //                         ->getOneAvecArtistes($id);
                        
    //                     $nbVues=$data[$c+1];
    //                     $txt[]=array($video,$nbVues);
    //                 }
    //             }
    //         }
    //         fclose($handle);
    //     }
        
    //     return $this->render('SpicySiteBundle:Site:topMois.html.twig',array(
    //         'txt'=>$txt
    //     ));
    // }    
    
    public function genresAction() 
    {
        $serializer = $this->get('serializer');
        $genreRepo = $this->get('mimizik.repository.genreMusical');
        
        $genres = $genreRepo->getAllGenres();
        
        if ($genres == null) {
            throw $this->createNotFoundException('Genres inexistant');
        }
        
        return new JsonResponse($serializer->serialize($genres, 'json'), 200);
    }

    public function redirectYoutubeAction($url = null, $plus = null)
    {
        if ($url == null) {
            $url = 'https://www.youtube.com/user/mimizikcom';
        } else {
            $url = 'https://www.youtube.com/channel/' . $url . '/' . $plus;
        }

        return $this->redirect($url);
    }

    public function redirectDefaultAction()
    {
        return $this->redirect($this->generateUrl('mimizik_ranking_index',array('page' => 1)));
    }

    public function redirectFeedbackAction(Request $request)
    {
        $href = $request->query->get('href');
        $href = 'http://' . $href;

        return $this->redirect($href);
    }    

    /**
     * 
     * @param type $page
     * @return type
     */
    public function showTopsAction(EntityManager $em, $page)
    {
        if ($page == '__id__') {
            throw new BadRequestHttpException("Ressource introuvable");
        }

        $nbSuggestion = $this->container->getParameter('nbSuggestion');

        $tops = $em->getRepository('SpicySiteBundle:Video')
            ->getTops($page, $nbSuggestion);

        return $this->render('SpicySiteBundle:Site:tops.html.twig', array(
            'videos' => $tops,
            'nombrePage' => ceil((count($tops)) / $nbSuggestion),
            'page' => $page
        ));
    }

    public function selectArtistesAction(EntityManager $em)
    {
        $selectArtistes = $em->getRepository(Artiste::class)->getHindAll();

        return $this->render('SpicySiteBundle:Site:Artiste\\_search.html.twig', array(
            'selectArtistes' => $selectArtistes
        ));
    }
    
    public function selectArtistesForwardAction() {
        return $this->forward('SpicySiteBundle:Site:selectArtistes');
    }
    
    /**
     * 
     * @param Interview $itw
     * @return type
     * @ParamConverter("itw", class="SpicyITWBundle:Interview")
     */
    // public function showInterviewAction(Interview $itw)
    // {
    //     return $this->render('SpicyITWBundle:Interview:showInterview.html.twig', [
    //         'itw' => $itw
    //     ]);
    // }
    
    /**
     * 
     * @param Artiste $artiste
     * @return type
     * @ParamConverter("artiste", class="SpicySiteBundle:Artiste", options={"id" = "id_artiste"})
     */
    public function showLastInterviewAction(EntityManagerInterface $em, VideoService $videoManager, Artiste $artiste)
    {        
        $itw = $em->getRepository('SpicyITWBundle:Interview')->getLastByArtiste($artiste);
        $videos = $videoManager->getLastVideos($artiste, 3);

        return $this->render('SpicyITWBundle:Interview:showInterview.html.twig', [
            'itw' => $itw,
            'videos' => $videos
        ]);
    }
    
    public function videoJsonAction($id)
    {
        $video = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')->getJson($id);

        $response = new Response(json_encode($video), 200);
    
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');
    
        return $response;
    }
}
