<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\NotBlank()
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * 
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tag_facebook", type="string", length=255, nullable=true)
     */
    private $tag_facebook;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tag_twitter", type="string", length=255, nullable=true)
     */
    private $tag_twitter;
    
    /**
     * @ORM\ManyToMany(targetEntity="Spicy\TagBundle\Entity\Hashtag")
     * @Assert\Valid()
    */
    private $hashtags;
    
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
    
    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="text", nullable=true) 
     */
    private $instagram;
    
    /**
    * @ORM\OneToMany(targetEntity="Spicy\ITWBundle\Entity\Interview", mappedBy="artiste")
     * @Assert\Valid()
    */
    private $interviews;

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

    /**
     * Set tag_facebook
     *
     * @param string $tagFacebook
     * @return Artiste
     */
    public function setTagFacebook($tagFacebook)
    {
        $this->tag_facebook = $tagFacebook;
    
        return $this;
    }

    /**
     * Get tag_facebook
     *
     * @return string 
     */
    public function getTagFacebook()
    {
        return $this->tag_facebook;
    }

    /**
     * Set tag_twitter
     *
     * @param string $tagTwitter
     * @return Artiste
     */
    public function setTagTwitter($tagTwitter)
    {
        $this->tag_twitter = $tagTwitter;
    
        return $this;
    }

    /**
     * Get tag_twitter
     *
     * @return string 
     */
    public function getTagTwitter()
    {
        return $this->tag_twitter;
    }

    /**
     * Set hashtags
     *
     * @param string $hashtags
     * @return Artiste
     */
    public function setHashtags($hashtags)
    {
        $this->hashtags = $hashtags;
    
        return $this;
    }

    /**
     * Get hashtags
     *
     * @return string 
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }

    /**
     * Set instagram
     *
     * @param string $instagram
     * @return Artiste
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    
        return $this;
    }

    /**
     * Get instagram
     *
     * @return string 
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Add hashtags
     *
     * @param \Spicy\TagBundle\Entity\Hashtag $hashtags
     * @return Artiste
     */
    public function addHashtag(\Spicy\TagBundle\Entity\Hashtag $hashtags)
    {
        $this->hashtags[] = $hashtags;
    
        return $this;
    }

    /**
     * Remove hashtags
     *
     * @param \Spicy\TagBundle\Entity\Hashtag $hashtags
     */
    public function removeHashtag(\Spicy\TagBundle\Entity\Hashtag $hashtags)
    {
        $this->hashtags->removeElement($hashtags);
    }

    /**
     * Add interviews
     *
     * @param \Spicy\ITWBundle\Entity\Interview $interviews
     * @return Artiste
     */
    public function addInterview(\Spicy\ITWBundle\Entity\Interview $interviews)
    {
        $this->interviews[] = $interviews;

        return $this;
    }

    /**
     * Remove interviews
     *
     * @param \Spicy\ITWBundle\Entity\Interview $interviews
     */
    public function removeInterview(\Spicy\ITWBundle\Entity\Interview $interviews)
    {
        $this->interviews->removeElement($interviews);
    }

    /**
     * Get interviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInterviews()
    {
        return $this->interviews;
    }
}
