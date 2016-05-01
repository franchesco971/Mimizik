<?php

namespace Spicy\FluxBundle\Services;

use Spicy\SiteBundle\Entity\Video;

class Twitter
{
    private $titre;
    private $description;
    
    const MAX_TYPES=117;//130-13


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

            if($nbTypeTitre<self::MAX_TYPES)
            {
                $description=$this->getDescriptionTwitterTag($video, $nbTypeTitre);
                $description=$this->getDescriptionHashtag($video,$nbTypeTitre,$description);
            }
            
            $descriptions[$video->getId()]=$description;
        }
        
        return $descriptions;
    }
    
    public function getArrayHashtags(Video $video) 
    {
        $hashtags=$txtHashtag='';
        $tabHashtags=$video->getHashtags();
        
        foreach ($video->getArtistes() as $artiste) 
        { 
            foreach ($artiste->getHashtags() as $hashtag) 
            {
                if(!$tabHashtags->contains($hashtag))
                {
                    $tabHashtags->add($hashtag);
                }                
            }
        }
        
        return $tabHashtags->toArray();
    }
    
    public function getArrayTwitterTags(Video $video) {
        $tags=$txTtag='';
        $tabTags=array();
        
        foreach ($video->getArtistes() as $artiste) 
        {
            if($tags=='')
            {
                $tags=$tags.$artiste->getTagTwitter();
            }
            elseif($artiste->getTagTwitter()!='')
            {
                $tags=$tags.';'.$artiste->getTagTwitter();
            }
        }
        
        if($tags=='')
        {
            $tags=$tags.$video->getTagsTwitter();
        }
        elseif($video->getTagsTwitter()!='')
        {
            $tags=$tags.';'.$video->getTagsTwitter();
        }
        
        if($tags!='')
        {
            $tabTags=explode(";", $tags);
        }
        
        return $tabTags;
    }
    
    public function getDescriptionTwitterTag(Video $video,$nbTotalTypes) 
    {
        $nbTypeTwitterTag=0;
        $description='';
        
        $arrayTwitterTags=$this->getArrayTwitterTags($video);
        foreach ($arrayTwitterTags as $twitterTag) {
            $nbTotalTypes=$nbTotalTypes+$nbTypeTwitterTag;
            if($nbTotalTypes<self::MAX_TYPES)
            {
                $nbTypeTwitterTag=$nbTypeTwitterTag+strlen($twitterTag);
                $description=$description.'@'.$twitterTag.' ';
            }
        }
        
        return $description;
    }
    
    public function getDescriptionHashtag($video,$nbTitreTypes,$description) 
    {
        $nbTypeHashtag=0;
        
        if(strlen($description.' #clip #mimizik ')<  self::MAX_TYPES)
        {
            $description=$description.' #clip #mimizik ';
            $nbTotalTypes=$nbTitreTypes+strlen($description);

            $arrayHastags=$this->getArrayHashtags($video); 
            foreach ($arrayHastags as $hashtag) {
                $nbTotalTypes=$nbTotalTypes+$nbTypeHashtag;
                if($nbTotalTypes<self::MAX_TYPES)
                {
                    $nbTypeHashtag=$nbTypeHashtag+strlen($hashtag->getLibelle());
                    $description=$description.'#'.$hashtag->getLibelle().' ';
                }            
            }
        }
        return $description;
    }
}
?>
