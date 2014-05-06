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
        $hashtags=$txtHashtag='';
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
            $tabHashtags=array_unique($tabHashtags);
            foreach ($tabHashtags as $hashtag) 
            {               
                    $txtHashtag=$txtHashtag.'#'.$hashtag.' ';                
            }
            
        }
        
        return $txtHashtag;
    }
}
?>
