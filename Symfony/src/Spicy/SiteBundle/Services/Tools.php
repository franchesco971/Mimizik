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
}
