<?php

namespace Spicy\SiteBundle\Services;

use Spicy\SiteBundle\Entity\Artiste;
use Spicy\SiteBundle\Entity\Video;

class Social
{
    public function getFacebookLink(Artiste $artiste)
    {
        $link='';
        $tagFb=$artiste->getTagFacebook();
        
        if($tagFb!='')
        {
            $link='https://www.facebook.com/';
        }
        
        $link=$link.$tagFb;
        
        return $link;
    }
    
    public function getInstagramLink(Artiste $artiste)
    {
        $link='';
        $instagram=$artiste->getInstagram();
        
        if($instagram!='')
        {
            $link='https://www.instagram.com/';
        }
        
        $link=$link.$instagram;
        
        return $link;
    }
    
    public function getArrayTwitterLink(Artiste $artiste) 
    {
        $arrayTags=$this->getArrayTag(';',$artiste->getTagTwitter());
        $links=array();
        
        foreach ($arrayTags as $tagFb) 
        {
            if($tagFb!='')
            {
                $links[]='https://twitter.com/'.$tagFb;
            }
        } 
        
        return $links;
    }
    
    public function getArrayTag($separator,$separateString) {
        return explode($separator, $separateString);
    }
    
    public function getHashtags(Video $video)
    {
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
        
        return $tabHashtags;
    }
}
?>
