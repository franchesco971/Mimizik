<?php

namespace Spicy\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Form\VideoType;
use Spicy\SiteBundle\Entity\TypeVideo;
use Spicy\SiteBundle\Form\TypeVideoType;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Form\GenreMusicalType;
use Spicy\SiteBundle\Entity\Artiste;
use Spicy\SiteBundle\Form\ArtisteType;


class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('SpicySiteBundle:Admin:index.html.twig');
    }
    
    public function homeVideoAction($page)
    {
        $nbVideoAfficheAdmin=$this->container->getParameter('nbVideoAfficheAdmin');
        
        $videos=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->getAll($page,$nbVideoAfficheAdmin);
        
        if($videos == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
               
        return $this->render('SpicySiteBundle:Admin:homeVideo.html.twig',array(
            'videos'=>$videos,
            'nombrePage'=>ceil((count($videos))/ $nbVideoAfficheAdmin),
            'page'=>$page
        ));
    }
    
    public function addVideoAction()
    {
        $video=new Video;
        
        $form= $this->createForm(new VideoType,$video);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Video bien ajouté');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:addVideo.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function updateVideoAction($id)
    {       
        $video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->find($id);
        
        if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $form= $this->createForm(new VideoType,$video);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Vidéo bien modifié');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:updateVideo.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function deleteVideoAction(Video $video)
    {
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Video bien supprimé');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:deleteVideo.html.twig',array(
            'form'=>$form->createView(),
            'artiste'=>$video
        ));
    }
    
    public function homeArtisteAction($page)
    {        
        $nbArtisteAffiche=$this->container->getParameter('nbArtisteAfficheAdmin');
        
        $artistes=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->getAll($page,$nbArtisteAffiche);
         
        if($artistes == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }
               
        return $this->render('SpicySiteBundle:Admin:homeArtiste.html.twig',array(
            'artistes'=>$artistes,
            'nombrePage'=>ceil((count($artistes))/ $nbArtisteAffiche),
            'page'=>$page
        ));
    }
    
    public function addArtisteAction()
    {
        $artiste=new Artiste;
        
        $form= $this->createForm(new ArtisteType,$artiste);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artiste);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Artiste bien ajouté');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:addArtiste.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function updateArtisteAction($id)
    {       
        $artiste=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->find($id);
        
        if ($artiste == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }
        
        $form= $this->createForm(new ArtisteType,$artiste);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artiste);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Artiste bien modifié');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:updateArtiste.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function deleteArtisteAction(Artiste $artiste)
    {
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($artiste);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Artiste bien supprimé');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:deleteArtiste.html.twig',array(
            'form'=>$form->createView(),
            'artiste'=>$artiste
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
       
       return $this->render('SpicySiteBundle:Admin:homeType_video.html.twig',array(
            'types'=>$types
        ));
    }
    
    public function addType_videoAction()
    {
        $type_video=new TypeVideo;
        
        $form= $this->createForm(new TypeVideoType,$type_video);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($type_video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Type bien ajouté');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:addType_video.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function updateType_videoAction($id)
    {       
        $type_video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:TypeVideo')
                ->find($id);
        
        if ($type_video == null) {
            throw $this->createNotFoundException('Type inexistant');
        }
        
        $form= $this->createForm(new TypeVideoType,$type_video);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($type_video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Type bien modifié');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:updateType_video.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function deleteType_videoAction(TypeVideo $type_video)
    {
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($type_video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Type bien supprimé');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:deleteType_video.html.twig',array(
            'form'=>$form->createView(),
            'type'=>$type_video
        ));
    }
    
    public function homeGenre_musicalAction()
    {
       $genres = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:GenreMusical')
                ->findAll();
       
       /*if ($genres == null) {
            throw $this->createNotFoundException('Genres inexistant');
          }*/
       
       return $this->render('SpicySiteBundle:Admin:homeGenre_musical.html.twig',array(
            'genres'=>$genres
        ));
    }
    
    public function addGenre_musicalAction()
    {
        $genre_musical=new GenreMusical;
        
        $form= $this->createForm(new GenreMusicalType,$genre_musical);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre_musical);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Genre bien ajouté');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:addGenre_musical.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function updateGenre_musicalAction($id)
    {       
        $genre_musical=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:GenreMusical')
                ->find($id);
        
        if ($genre_musical == null) {
            throw $this->createNotFoundException('Genre inexistant');
        }
        
        $form= $this->createForm(new GenreMusicalType,$genre_musical);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre_musical);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Genre bien modifié');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:updateGenre_musical.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function deleteGenre_musicalAction(GenreMusical $genre_musical)
    {
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($genre_musical);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Genre bien supprimé');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:deleteGenre_musical.html.twig',array(
            'form'=>$form->createView(),
            'genre'=>$genre_musical
        ));
    }
    
    public function csvAction() 
    {
        
        $row = 1;
        $tab=array();
        $tablo[]='/video/3/test,5';
        $tablo[]='/video/3/test,5';
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
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                //$txt=$txt."<p> $num champs à la ligne $row: <br /></p>\n";
                //$txt=array();
                
                for ($c=0; $c < $num; $c++) {
                //for ($c=0; $c < 1; $c++) {    
                    if(preg_match('/^\/video/', $data[$c]))
                    {
                        list($rien,$debut,$id,$reste)=  explode('/',$data[$c] );
                        //list($rien,$debut,$id,$reste)=  explode('/',$tablo[$c] );
                        $video=$this->getDoctrine()
                            ->getManager()
                            ->getRepository('SpicySiteBundle:Video')
                            ->getOneAvecArtistes($id);
                        
                        $nbVues=$data[$c+1];
                        //$nbVues=$tablo[$c+1];
                        $txt[]=array($video,$nbVues);
                    }
                    //$txt[]=$data[$c];
                }
                //$tab[$row]=$txt;
                //$row++;
            }
            fclose($handle);
        }
        
        return $this->render('SpicySiteBundle:Admin:csv.html.twig',array(
            'txt'=>$txt
        ));
    }
}
