<?php

namespace Spicy\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Hashtag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Spicy\TagBundle\Entity\HashtagRepository")
 */
class Hashtag
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateTag", type="datetime")
     */
    private $dateTag;


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
     * @return Hashtag
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
     * Set dateTag
     *
     * @param \DateTime $dateTag
     * @return Hashtag
     */
    public function setDateTag($dateTag)
    {
        $this->dateTag = $dateTag;
    
        return $this;
    }

    /**
     * Get dateTag
     *
     * @return \DateTime 
     */
    public function getDateTag()
    {
        return $this->dateTag;
    }
}
