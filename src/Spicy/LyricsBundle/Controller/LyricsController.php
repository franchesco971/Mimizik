<?php

namespace Spicy\LyricsBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Spicy\LyricsBundle\Entity\Lyrics;
use Spicy\LyricsBundle\Form\LyricsType;
use Spicy\SiteBundle\Services\Tools;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Spicy\SiteBundle\Entity\Video;

/**
 * LyricsController
 */
class LyricsController extends Controller
{
    /**
     * @Route("/admin/paroles/update/{id}", name="admin_lyrics_update", methods={"GET", "POST"}, requirements={"id"="\d+"})
     * @ParamConverter("video", class="SpicySiteBundle:Video")
     */
    public function updateAction(Request $request, EntityManagerInterface $em, $video)
    {
        $lyrics = $video->getLyrics();

        if (!$lyrics) {
            $lyrics = new Lyrics();
            $video->setLyrics($lyrics);
        }

        $form = $this->createForm(LyricsType::class, $lyrics);
        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $em->persist($lyrics);
                foreach ($lyrics->getParagraphs() as $p) {
                    $p->setLyrics($lyrics);
                    $em->persist($p);
                }

                $em->flush();

                $this->get('session')->getFlashBag()->add('info', 'Paroles bien ajouté');

                return $this->redirect($this->generateUrl('spicy_admin_home_video'));
            }
        }

        return $this->render('SpicyLyricsBundle:Admin:Lyrics/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * 
     * @param string $id
     * @return type
     * @Route("/paroles/{id}/{slug}", name="mimizik_paroles_show", methods={"GET"}, requirements={"id"="\d+"})
     * @ParamConverter("video", class="SpicySiteBundle:Video")
     */
    public function showAction(Tools $toolsManager, EntityManagerInterface $em, $video)
    {
        /** A REFAIRE ***/
        $genres = $video->getGenreMusicaux();
        $genreIdsList = $toolsManager->getListId($genres);

        $suggestions = $em->getRepository(Video::class)->getSuggestions($genreIdsList, $video->getId());

        return $this->render('SpicyLyricsBundle::show.html.twig', [
            'video' => $video,
            'lyrics' => $em->getRepository(Lyrics::class)->getOrdered($video),
            'suggestions' => $suggestions
        ]);
    }
}
