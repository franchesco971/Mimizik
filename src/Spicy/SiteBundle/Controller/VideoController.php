<?php

namespace Spicy\SiteBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Spicy\SiteBundle\Entity\Video;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;

/**
 * Description of VideoController
 *
 * @author franciscopol
 */
class VideoController extends Controller
{
    /**
     * searchVideo
     *
     * @param EntityManagerInterface $em
     * @param Serializer $serializer
     * @param integer $page
     * @param string $term
     * @return JsonResponse
     */
    public function searchVideoAction(Request $request, EntityManagerInterface $em, Serializer $serializer, $page = 1)
    {
        $term = urldecode($request->request->get('term'));
        $maxResult = $request->request->get('maxResult');

        if (!$maxResult) {
            $maxResult = 10;
        }
        
        $countLetter = strlen($term);

        if ($countLetter <= 2) {
            return new JsonResponse(['message' => "Letter numbers lower than 3"], 500);
        }
        
        $videos = $em->getRepository(Video::class)->getSearchedVideo($term, true, $maxResult);

        if (count($videos) == 0) {
            return new JsonResponse("No result", 400);
        }
        
        return new JsonResponse($serializer->serialize($videos, 'json'), 200);
    }
}
