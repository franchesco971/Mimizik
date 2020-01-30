<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * GenreMusical
 *
 * @ORM\Table(name="Genre_musical")
 * @ORM\Entity(repositoryClass="Spicy\SiteBundle\Repository\GenreMusicalRepository")
 */
class GenreMusical
{
    const RETRO=2;
    
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
     * @ORM\Column(name="dateGenre_musical", type="datetime")
     */
    private $dateGenreMusical;
    
    /**
   * @Gedmo\Slug(fields={"libelle"})
   * @ORM\Column(length=128, unique=true)
   */
    private $slug;

    public function __construct() {
        $this->dateGenreMusical= new \DateTime;
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
     * @return Genre_musical
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
     * Set dateGenreMusical
     *
     * @param \DateTime $dateGenreMusical
     * @return Genre_musical
     */
    public function setDateGenreMusical($dateGenreMusical)
    {
        $this->dateGenreMusical = $dateGenreMusical;
    
        return $this;
    }

    /**
     * Get dateGenreMusical
     *
     * @return \DateTime 
     */
    public function getDateGenreMusical()
    {
        return $this->dateGenreMusical;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return GenreMusical
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