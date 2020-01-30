<?php

namespace Spicy\SiteBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Form\VideoType;
use Spicy\SiteBundle\Entity\TypeVideo;
use Spicy\SiteBundle\Form\TypeVideoType;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Form\GenreMusicalType;
use Spicy\SiteBundle\Entity\Artiste;
use Spicy\SiteBundle\Form\ArtisteType;
use Spicy\SiteBundle\Entity\Collaborateur;
use Spicy\SiteBundle\Form\CollaborateurType;
use Spicy\SiteBundle\Services\YoutubeAPI;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Spicy\AppBundle\Services\FacebookManager;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('SpicySiteBundle:Admin:index.html.twig');
    }

    public function homeVideoAction($page, EntityManagerInterface $em)
    {
        $nbVideoAfficheAdmin = $this->container->getParameter('nbVideoAfficheAdmin');

        $selectVideos = $em
            ->getRepository('SpicySiteBundle:Video')
            ->findAll();

        if ($selectVideos == null) {
            throw $this->createNotFoundException('Videos inexistant');
        }

        $videos = $em
            ->getRepository('SpicySiteBundle:Video')
            ->getAll($page, $nbVideoAfficheAdmin);

        if ($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        return $this->render('SpicySiteBundle:Admin:homeVideo.html.twig', array(
            'selectVideos' => $selectVideos,
            'videos' => $videos,
            'nombrePage' => ceil((count($videos)) / $nbVideoAfficheAdmin),
            'page' => $page
        ));
    }

    /**
     * addVideoAction
     *
     * @param Request $request
     * @param YoutubeAPI $youtubeAPI
     * @param EntityManagerInterface $em
     * @throws UniqueConstraintViolationException
     * @throws Exception
     * @return void
     */
    public function addVideoAction(Request $request, YoutubeAPI $youtubeAPI, EntityManagerInterface $em, LoggerInterface $logger)
    {
        $yurl = $request->query->get('youtubeUrl');
        $video = new Video();

        if ($yurl) //s'il y a une url youtube
        {
            $video = $youtubeAPI->getByYoutubeId($yurl);
        }

        $form = $this->createForm(VideoType::class, $video);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {                

                try {
                    $em->persist($video);
                    $em->flush();
                } catch (UniqueConstraintViolationException $ex) {
                    $logger->error("[addVideoAction] : Video existante -> " . $ex->getMessage(), ['url' => $yurl]);
                    $this->get('session')->getFlashBag()->add('error', 'Video existante');
                    return $this->redirect($this->generateUrl('spicy_admin_home'));
                } catch (\Throwable $th) {
                    $logger->error("[addVideoAction] : Erreur enregistrement video -> " . $th->getMessage());
                    $this->get('session')->getFlashBag()->add('error', 'Erreur enregistrement video');
                    return $this->redirect($this->generateUrl('spicy_admin_home'));
                }

                $logger->info("[addVideoAction] : Video bien ajouté", ['id' => $video->getId()]);
                $this->get('session')->getFlashBag()->add('info', 'Video bien ajouté');
                $_SESSION['id_video_publish'] = $video->getId();

                return $this->redirect($this->generateUrl('spicy_admin_home'));

            }
        }

        return $this->render('SpicySiteBundle:Admin:addVideo.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @return type
     */
    public function addAdminVideoYoutubeAction(Request $request)
    {
        $video = new Video;

        $videotype = $request->request->get('video');
        $url = $videotype['url'];

        $form = $this->createForm(VideoType::class, $video, ['video' => false]);

        if ($request->getMethod() == 'POST') {
            return $this->redirect($this->generateUrl('approval_new_admin', array('youtubeUrl' => $url)));
        }

        return $this->render('SpicySiteBundle:Admin:addVideoYoutube.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function addVideoYoutubeAction(Request $request)
    {        
        $video = new Video;

        $videotype = $request->request->get('video');
        $url = $videotype['url'];
        $form = $this->createForm(VideoType::class, $video, ['video' => false]);

        if ($request->getMethod() == 'POST') {
            return $this->redirect($this->generateUrl('spicy_admin_add_video', array('youtubeUrl' => $url)));
        }

        return $this->render('SpicySiteBundle:Admin:addVideoYoutube.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * 
     * @param String $id
     * @return view
     * @throws NotFoundHttpException
     */
    public function updateVideoAction(Request $request, EntityManagerInterface $em, $id)
    {
        $video = $em->getRepository('SpicySiteBundle:Video')->getOneForUpdate($id);

        if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }

        // $oldState = $video->getEtat();

        $form = $this->createForm(VideoType::class, $video);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($video);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Vidéo bien modifié');

                //Si le status de la video passe à true, on publie
                //if($oldState == false && $video->getEtat() == true) {
                //    $_SESSION['id_video_publish'] = $video->getId();
                //     return $this->redirect($this->generateUrl('mimizik_app_fb_login'));
                // } else {
                return $this->redirect($this->generateUrl('spicy_admin_home_video'));
                //}        
            }
        }

        return $this->render('SpicySiteBundle:Admin:updateVideo.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteVideoAction(Request $request, Video $video, EntityManagerInterface $em)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->remove($video);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Video bien supprimé');

                return $this->redirect($this->generateUrl('spicy_admin_home_video'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:deleteVideo.html.twig', array(
            'form' => $form->createView(),
            'artiste' => $video
        ));
    }

    public function homeArtisteAction(EntityManagerInterface $em, $page)
    {
        $nbArtisteAffiche = $this->container->getParameter('nbArtisteAfficheAdmin');

        $selectArtistes = $em
            ->getRepository('SpicySiteBundle:Artiste')
            ->findAll();

        if ($selectArtistes == null) {
            throw $this->createNotFoundException('Artistes inexistant');
        }

        $artistes = $em
            ->getRepository('SpicySiteBundle:Artiste')
            ->getAll($page, $nbArtisteAffiche);

        if ($artistes == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }

        return $this->render('SpicySiteBundle:Admin:homeArtiste.html.twig', array(
            'artistes' => $artistes,
            'selectArtistes' => $selectArtistes,
            'nombrePage' => ceil((count($artistes)) / $nbArtisteAffiche),
            'page' => $page
        ));
    }

    public function addArtisteAction(Request $request)
    {
        $artiste = new Artiste;

        $form = $this->createForm(ArtisteType::class, $artiste);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($artiste);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Artiste bien ajouté');

                return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:addArtiste.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function updateArtisteAction(EntityManager $em, Request $request, $id)
    {
        $artiste = $em
            ->getRepository('SpicySiteBundle:Artiste')
            ->find($id);

        if ($artiste == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }

        $form = $this->createForm(ArtisteType::class, $artiste);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($artiste);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Artiste bien modifié');

                return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:updateArtiste.html.twig', array(
            'form' => $form->createView(),
            'idArtiste' => $id
        ));
    }

    /**
     * 
     * @param Artiste $artiste
     * @return type
     */
    public function deleteArtisteAction(Request $request, Artiste $artiste)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($artiste);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Artiste bien supprimé');

                return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:deleteArtiste.html.twig', array(
            'form' => $form->createView(),
            'artiste' => $artiste
        ));
    }

    public function homeType_videoAction()
    {
        $types = $this->getDoctrine()
            ->getManager()
            ->getRepository('SpicySiteBundle:TypeVideo')
            ->findAll();

        /*if ($types == null) {
            throw $this->createNotFoundException('Types inexistant');
          }*/

        return $this->render('SpicySiteBundle:Admin:homeType_video.html.twig', array(
            'types' => $types
        ));
    }

    public function addType_videoAction(Request $request)
    {
        $type_video = new TypeVideo;

        $form = $this->createForm(TypeVideoType::class, $type_video);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($type_video);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Type bien ajouté');

                return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:addType_video.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function updateType_videoAction(Request $request, $id)
    {
        $type_video = $this->getDoctrine()
            ->getManager()
            ->getRepository('SpicySiteBundle:TypeVideo')
            ->find($id);

        if ($type_video == null) {
            throw $this->createNotFoundException('Type inexistant');
        }

        $form = $this->createForm(TypeVideoType::class, $type_video);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($type_video);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Type bien modifié');

                return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:updateType_video.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteType_videoAction(Request $request, TypeVideo $type_video)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($type_video);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Type bien supprimé');

                return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:deleteType_video.html.twig', array(
            'form' => $form->createView(),
            'type' => $type_video
        ));
    }

    public function homeGenre_musicalAction(EntityManagerInterface $em)
    {
        $genres = $em->getRepository('SpicySiteBundle:GenreMusical')->findAll();

        /*if ($genres == null) {
            throw $this->createNotFoundException('Genres inexistant');
          }*/

        return $this->render('SpicySiteBundle:Admin:homeGenre_musical.html.twig', array(
            'genres' => $genres
        ));
    }

    public function addGenre_musicalAction(Request $request)
    {
        $genre_musical = new GenreMusical;

        $form = $this->createForm(GenreMusicalType::class, $genre_musical);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($genre_musical);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Genre bien ajouté');

                return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:addGenre_musical.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function updateGenre_musicalAction(Request $request, $id)
    {
        $genre_musical = $this->getDoctrine()
            ->getManager()
            ->getRepository('SpicySiteBundle:GenreMusical')
            ->find($id);

        if ($genre_musical == null) {
            throw $this->createNotFoundException('Genre inexistant');
        }

        $form = $this->createForm(GenreMusicalType::class, $genre_musical);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($genre_musical);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Genre bien modifié');

                return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:updateGenre_musical.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteGenre_musicalAction(Request $request, GenreMusical $genre_musical)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($genre_musical);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Genre bien supprimé');

                return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
            }
        }

        return $this->render('SpicySiteBundle:Admin:deleteGenre_musical.html.twig', array(
            'form' => $form->createView(),
            'genre' => $genre_musical
        ));
    }

    public function csvAction()
    {

        $row = 1;
        $tab = array();
        $tablo[] = '/video/3/test,5';
        $tablo[] = '/video/3/test,5';
        //$tablo[]=5;
        /*$tablo[]='/video/6/test';
        //$tablo[]=6;
        $tablo[]='/video/7/test';
        //$tablo[]=7;
        $tablo[]='/video/9/test';
        //$tablo[]=8;
        $tablo[]='/video/10/test';
        //$tablo[]=9;
        
        $tablo[]='/video/11/test';
        //$tablo[]=10;
        $tablo[]='/video/6/test';
        //$tablo[]=11;
        $tablo[]='/video/7/test';
        //$tablo[]=12;
        $tablo[]='/video/9/test';
        //$tablo[]=13;*/

        if (($handle = fopen("csv/test4.csv", "r")) !== FALSE) {
            var_dump('cool');
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                //$txt=$txt."<p> $num champs à la ligne $row: <br /></p>\n";
                //$txt=array();

                for ($c = 0; $c < $num; $c++) {
                    //for ($c=0; $c < 1; $c++) {    
                    if (preg_match('/^\/video/', $data[$c])) {
                        list($rien, $debut, $id, $reste) =  explode('/', $data[$c]);
                        //list($rien,$debut,$id,$reste)=  explode('/',$tablo[$c] );
                        $video = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SpicySiteBundle:Video')
                            ->getOneAvecArtistes($id);

                        $nbVues = $data[$c + 1];
                        //$nbVues=$tablo[$c+1];
                        $txt[] = array($video, $nbVues);
                    }
                    //$txt[]=$data[$c];
                }
                //$tab[$row]=$txt;
                //$row++;
            }
            fclose($handle);
        }

        return $this->render('SpicySiteBundle:Admin:csv.html.twig', array(
            'txt' => $txt
        ));
    }

    public function modalNewCollaborateurAction()
    {
        $entity = new Collaborateur();
        $form   = $this->createForm(CollaborateurType::class, $entity);

        return $this->render('SpicySiteBundle:Admin:Collaborateur/modalNew.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    public function modalCreateCollaborateurAction(Request $request, EntityManagerInterface $em)
    {
        $entity  = new Collaborateur();
        $refererUrl = $request->headers->get("referer");
        $form = $this->createForm(CollaborateurType::class, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($refererUrl);
        }

        $this->get('session')->getFlashBag()->add(
            'notice',
            'Erreur dans la creation du collaborateur'
        );

        return $this->redirect($refererUrl);
    }

    public function testFBAction(FacebookManager $facebookManager)
    {
        $facebookManager = $facebookManager->getFacebookObject();

        return $this->render('SpicySiteBundle:Admin:index.html.twig');
    }
}
