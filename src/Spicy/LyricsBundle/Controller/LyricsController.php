<?php

namespace Spicy\LyricsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Spicy\LyricsBundle\Entity\Lyrics;
use Spicy\SiteBundle\Form\TitleType;
use Spicy\LyricsBundle\Entity\Paragraph;
use Spicy\LyricsBundle\Form\LyricsType;

class LyricsController extends Controller
{
    public function updateAction(Request $request,$id)
    {
        $em=  $this->getDoctrine()->getManager();
        
        $video=$em->getRepository('SpicySiteBundle:Video')->find($id);
        $lyrics=$video->getLyrics();

        if(!$lyrics)
        {
            $lyrics=new Lyrics();
            $video->setLyrics($lyrics);
        }
               
        $form= $this->createForm(new LyricsType,$lyrics);
        if ($request->getMethod() == 'POST') {
            
            $form->bind($request);           
           
            if ($form->isValid()) { 
                $em->persist($lyrics);
                foreach ($lyrics->getParagraphs() as $p) {
                    $p->setLyrics($lyrics);
                    $em->persist($p);
                }
                
                $em->flush();

                $this->get('session')->getFlashBag()->add('info','Paroles bien ajouté');

                return $this->redirect($this->generateUrl('spicy_admin_home_video'));
                
            }
        }
        
        return $this->render('SpicyLyricsBundle:Admin:Lyrics/update.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
