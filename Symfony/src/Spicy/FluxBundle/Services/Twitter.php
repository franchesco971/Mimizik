<?php

namespace Spicy\FluxBundle\Services;

use Spicy\SiteBundle\Entity\Video;

class Twitter
{
    private $titre;
    private $description;
    
    public function __construct() {
        
    }


    public function twitterType($videos) 
    {
        $description='';
        $descriptions=array();
        
        foreach ($videos as $video) 
        {
            $this->titre=$video->getNomArtistes().' - '.$video->getTitre().': ';
            $nbTypeTitre=  strlen($this->titre);

            if($nbTypeTitre<140-13)
            {
                $description=$this->getDescriptionTwitterTag($video, $nbTypeTitre);
                $description=$this->getDescriptionHashtag($video,$nbTypeTitre,$description);
            }
            
            $descriptions[$video->getId()]=$description;
        }
        
        return $descriptions;
    }
    
    public function getArrayHashtags(Video $video) {
        $hashtags=$txtHashtag='';
        $tabHashtags=array();
        foreach ($video->getArtistes() as $artiste) {
            if($hashtags=='')
            {
                $hashtags=$hashtags.$artiste->getHashtags();
            }
            elseif($artiste->getHashtags()!='')
            {
                $hashtags=$hashtags.';'.$artiste->getHashtags();
            }
        }
        
        if($hashtags!='')
        {
            $tabHashtags=explode(";", $hashtags);
        }
        
        return $tabHashtags;
    }
    
    public function getArrayTwitterTags(Video $video) {
        $tags=$txTtag='';
        $tabTags=array();
        
        foreach ($video->getArtistes() as $artiste) {
            if($tags=='')
            {
                $tags=$tags.$artiste->getTagTwitter();
            }
            elseif($artiste->getTagTwitter()!='')
            {
                $tags=$tags.';'.$artiste->getTagTwitter();
            }
        }
        
        if($tags!='')
        {
            $tabTags=explode(";", $tags);
        }
        
        return $tabTags;
    }
    
    public function getDescriptionTwitterTag(Video $video,$nbTotalTypes) {
        $nbTypeTwitterTag=0;
        $description='';
        
        $arrayTwitterTags=$this->getArrayTwitterTags($video);
        foreach ($arrayTwitterTags as $twitterTag) {
            $nbTotalTypes=$nbTotalTypes+$nbTypeTwitterTag;
            if($nbTotalTypes<140-13)
            {
                $nbTypeTwitterTag=$nbTypeTwitterTag+strlen($twitterTag);
                $description=$description.'@'.$twitterTag.' ';
            }
            
        }
        
        return $description;
    }
    
    public function getDescriptionHashtag($video,$nbTitreTypes,$description) {
        $nbTypeHashtag=0;
        if(strlen($description.' #clip #mimizik ')<140-13)
        {
            $description=$description.' #clip #mimizik ';
            $nbTotalTypes=$nbTitreTypes+strlen($description);

            $arrayHastags=$this->getArrayHashtags($video);
            $arrayHastags=array_unique($arrayHastags);
            foreach ($arrayHastags as $hashtag) {
                $nbTotalTypes=$nbTotalTypes+$nbTypeHashtag;
                if($nbTotalTypes<140-13)
                {
                    $nbTypeHashtag=$nbTypeHashtag+strlen($hashtag);
                    $description=$description.'#'.$hashtag.' ';
                }            
            }
        }
        return $description;
    }
}
?>
