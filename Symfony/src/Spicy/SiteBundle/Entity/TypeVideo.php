<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeVideo
 *
 * @ORM\Table(name="type_video")
 * @ORM\Entity(repositoryClass="Spicy\SiteBundle\Entity\TypeVideoRepository")
 */
class TypeVideo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateType_video", type="datetime")
     */
    private $dateTypeVideo;

    public function __construct()
    {
        $this->dateTypeVideo=new \DateTime;
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return TypeVideo
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    
        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set dateTypeVideo
     *
     * @param \DateTime $dateTypeVideo
     * @return TypeVideo
     */
    public function setDateTypeVideo($dateTypeVideo)
    {
        $this->dateTypeVideo = $dateTypeVideo;
    
        return $this;
    }

    /**
     * Get dateTypeVideo
     *
     * @return \DateTime 
     */
    public function getDateTypeVideo()
    {
        return $this->dateTypeVideo;
    }
}