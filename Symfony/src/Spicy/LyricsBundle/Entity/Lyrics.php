<?php

namespace Spicy\LyricsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Lyrics
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Lyrics
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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;
    
    /**
    * @ORM\OneToMany(targetEntity="Spicy\LyricsBundle\Entity\Paragraph", mappedBy="lyrics")
    * @Assert\Valid()
    */
    private $paragraphs;
    
    /**
    * @ORM\OneToOne(targetEntity="Spicy\SiteBundle\Entity\Title")
    */
    private $title;


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paragraphs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Lyrics
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add paragraphs
     *
     * @param \Spicy\LyricsBundle\Entity\Paragraph $paragraphs
     * @return Lyrics
     */
    public function addParagraph(\Spicy\LyricsBundle\Entity\Paragraph $paragraphs)
    {
        $this->paragraphs[] = $paragraphs;

        return $this;
    }

    /**
     * Remove paragraphs
     *
     * @param \Spicy\LyricsBundle\Entity\Paragraph $paragraphs
     */
    public function removeParagraph(\Spicy\LyricsBundle\Entity\Paragraph $paragraphs)
    {
        $this->paragraphs->removeElement($paragraphs);
    }

    /**
     * Get paragraphs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParagraphs()
    {
        return $this->paragraphs;
    }

    /**
     * Set title
     *
     * @param \Spicy\SiteBundle\Entity\Title $title
     * @return Lyrics
     */
    public function setTitle(\Spicy\SiteBundle\Entity\Title $title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return \Spicy\SiteBundle\Entity\Title 
     */
    public function getTitle()
    {
        return $this->title;
    }
}
