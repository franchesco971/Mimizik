<?php

namespace Spicy\LyricsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Spicy\LyricsBundle\Entity\Lyrics;
use Spicy\SiteBundle\Form\TitleType;
use Spicy\LyricsBundle\Entity\Paragraph;

class LyricsController extends Controller
{
    public function updateAction(Request $request,$id)
    {
        $em=  $this->getDoctrine()->getManager();
        
        $video=$em->getRepository('SpicySiteBundle:Video')->find($id);
        if(!$lyrics=$video->getLyrics())
        {
            $lyrics=new Lyrics();
            $lyrics->addParagraph(new Paragraph);
            $video->setLyrics($lyrics);
        }
        
        $form= $this->createForm(new TitleType,$video);
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {           
                $em->persist($lyrics);
                $em->flush();

                $this->get('session')->getFlashBag()->add('info','Video bien ajouté');
                $_SESSION['id_video_publish']=$video->getId();

                return $this->redirect($this->generateUrl('mimizik_app_fb_login'));
            }
        }
        
        return $this->render('SpicyLyricsBundle:Admin:Lyrics/update.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
