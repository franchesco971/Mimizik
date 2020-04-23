<?php

namespace Spicy\SiteBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Spicy\SiteBundle\Entity\Artiste;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class ArtistController extends Controller
{
    /**
     * @Route("/site/bundle/artist/bundle", name="site_bundle_artist_bundle")
     */
    public function index()
    {
        return $this->render('site_bundle_artist_bundle/index.html.twig', [
            'controller_name' => 'SiteBundleArtistBundleController',
        ]);
    }

    /**
     * search Artist
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Serializer $serializer
     * @param integer $page
     * @param string $term
     * @return JsonResponse
     */
    public function searchArtistAction(Request $request, EntityManagerInterface $em, Serializer $serializer, $page = 1, string $term = '')
    {
        $term = urldecode($request->request->get('term'));
        $maxResult = $request->request->get('maxResult');

        if (!$maxResult) {
            $maxResult = 10;
        }
        
        $termLength = strlen($term);

        if ($termLength <= 2) {
            return new JsonResponse(['message' => "Letters number lower than 3"], 500);
        }
        
        $artists = $em->getRepository(Artiste::class)->getSearchedArtist($term, true, $maxResult, $page);

        if (count($artists) == 0) {
            return new JsonResponse("No result", 400);
        }
        
        return new JsonResponse($serializer->serialize($artists, 'json'), 200);
    }
}
