<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Artiste
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Spicy\SiteBundle\Entity\ArtisteRepository")
 */
class Artiste
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     *
     * @var string
     * @Gedmo\Slug(fields={"libelle"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateArtiste", type="datetime")
     */
    private $dateArtiste;

    public function __construct() {
        $this->dateArtiste=new \DateTime;
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
     * @return Artiste
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
     * Set description
     *
     * @param string $description
     * @return Artiste
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateArtiste
     *
     * @param \DateTime $dateArtiste
     * @return Artiste
     */
    public function setDateArtiste($dateArtiste)
    {
        $this->dateArtiste = $dateArtiste;
    
        return $this;
    }

    /**
     * Get dateArtiste
     *
     * @return \DateTime 
     */
    public function getDateArtiste()
    {
        return $this->dateArtiste;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Artiste
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
}