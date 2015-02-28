<?php

namespace Spicy\SiteBundle\Services;

/**
 * Description of Tools
 *
 * @author frfco
 */
class Tools {
    
    public function getListId($entities) 
    {
        $ids=array();
        foreach ($entities as $entity) 
        {
            $ids[]=$entity->getId();
        }
        
        return $ids;
    }
    
    public function getAllGenresforVideoCol($videos,$getID=false) 
    {
        $tabGenres=array();
        
        foreach ($videos as $video) {
            $genres=  $video->getGenreMusicaux();
            foreach ($genres as $genre) 
            {
                if(!in_array($genre, $tabGenres))
                {
                    $tabGenresID[]=$genre->getId();
                    $tabGenres[]=$genre;                    
                }
            }
        }
        
        if($getID==true)
        {
            return $tabGenresID;
        }
        else
        {
            return $tabGenres;
        }
        
    }
    
    public function getArtistesBySuggestions($suggestions,$idArtiste)
    {
        $tabArtistes=array();
        
        foreach ($suggestions as $suggestion)
        {
            foreach ($suggestion->getArtistes() as $sArtiste)
            {
                if(!in_array($sArtiste, $tabArtistes) && $sArtiste->getId()!=$idArtiste)
                {
                    $tabArtistes[]=$sArtiste;
                    
                }            
            }
        }
        
        return $tabArtistes;
    }
}
