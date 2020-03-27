<?php

namespace Spicy\SiteBundle\Services;

use Psr\Log\LoggerInterface;
use Spicy\SiteBundle\Entity\Artiste;

/**
 * Description of Tools
 *
 * @author frfco
 */
class Tools {
    
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function getListId($entities)
    {
        $ids = [];
        foreach ($entities as $entity) {
            $ids[] = $entity->getId();
        }

        return $ids;
    }

    public function getAllGenresforVideoCol($videos, $getID = false)
    {
        $tabGenres = $tabGenresID = [];

        foreach ($videos as $video) {
            $genres = $video->getGenreMusicaux();
            foreach ($genres as $genre) {
                if (!in_array($genre, $tabGenres)) {
                    $tabGenresID[] = $genre->getId();
                    $tabGenres[] = $genre;
                }
            }
        }

        if ($getID == true) {
            return $tabGenresID;
        }
            
        return $tabGenres;
    }

    public function getArtistesBySuggestions($suggestions, $idArtiste)
    {
        $tabArtistes = [];

        foreach ($suggestions as $suggestion) {
            foreach ($suggestion->getArtistes() as $sArtiste) {
                if (!in_array($sArtiste, $tabArtistes) && $sArtiste->getId() != $idArtiste) {
                    $tabArtistes[] = $sArtiste;
                }
            }
        }

        return $tabArtistes;
    }
    
    public function sendMail($message)
    {
        $smessage = (new \Swift_Message('Soumission de vidéo mimizik'))
        ->setFrom('franchesco971@mimizik.com')
        ->setTo('franchesco971@mimizik.com')
        ->setBody($message,'text/html');
        
        $this->mailer->send($smessage);
    }

    // public function getArtistsNames($artists, $maxNumber = 100)
    // {
    //     $text = '';
    //     $nbletter = 0;
    //     foreach ($artists as $key => $artist) {
    //         if ($nbletter < $maxNumber) {
    //             $label = $artist->getLibelle();
    //             $nbletter = $nbletter + strlen($label);
    //             $text = $text . $label;

    //             $text = $this->ponctuation($artists, $key, $text);
    //         }
    //     }

    //     return $text;
    // }

    public function ponctuation($artists, $key, $text)
    {
        if (count($artists) == 2 && $key == 0) {
            $text = $text . ' &amp; ';
        } elseif (count($artists) > 2 && count($artists) - $key > 2) {
            $text = $text . ', ';
        } elseif (count($artists) - $key == 2) {
            $text = $text . ' &amp; ';
        }

        return $text;
    }
    
    /**
     *  get Profil Picture
     *
     * @param Artiste $artiste
     * @return string
     */
    public function getProfilPic(Artiste $artiste)
    {
        $profilPic = "/images/user-male2.png";
        
        if ($artiste->getTagFacebook()) {
            $fbLink = "https://graph.facebook.com/".$artiste->getTagFacebook()."/picture?type=large";
        
            $responseAr = get_headers($fbLink);
            if ($responseAr[0] != "HTTP/1.1 404 Not Found") {
                $profilPic = $fbLink;
            }
        }        
        
        return $profilPic;
    }
}
