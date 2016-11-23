<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spicy\SiteBundle\Entity\Title;

/**
 * Track
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Track extends Title
{
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
