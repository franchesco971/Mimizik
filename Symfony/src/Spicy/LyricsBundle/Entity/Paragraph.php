<?php

namespace Spicy\LyricsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Paragraph
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Paragraph
{
    const INTRO=1;
    const COUPLET=2;
    const REFRAIN=3;
    const OUTRO=4;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="content_original", type="text")
     */
    private $contentOriginal;
    
    /**
     * @var string
     *
     * @ORM\Column(name="content_traduction", type="text")
     */
    private $contentTraduction;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="paragraph_type", type="integer")
     */
    private $paragraph_type;
    
    /**
    * @ORM\ManyToOne(targetEntity="Spicy\LyricsBundle\Entity\Lyrics")
    * @Assert\Valid()
    */
    private $lyrics;


    

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
     * Set position
     *
     * @param integer $position
     * @return Paragraph
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set contentOriginal
     *
     * @param string $contentOriginal
     * @return Paragraph
     */
    public function setContentOriginal($contentOriginal)
    {
        $this->contentOriginal = $contentOriginal;

        return $this;
    }

    /**
     * Get contentOriginal
     *
     * @return string 
     */
    public function getContentOriginal()
    {
        return $this->contentOriginal;
    }

    /**
     * Set contentTraduction
     *
     * @param string $contentTraduction
     * @return Paragraph
     */
    public function setContentTraduction($contentTraduction)
    {
        $this->contentTraduction = $contentTraduction;

        return $this;
    }

    /**
     * Get contentTraduction
     *
     * @return string 
     */
    public function getContentTraduction()
    {
        return $this->contentTraduction;
    }

    /**
     * Set paragraph_type
     *
     * @param integer $paragraphType
     * @return Paragraph
     */
    public function setParagraphType($paragraphType)
    {
        $this->paragraph_type = $paragraphType;

        return $this;
    }

    /**
     * Get paragraph_type
     *
     * @return integer 
     */
    public function getParagraphType()
    {
        return $this->paragraph_type;
    }

    /**
     * Set lyrics
     *
     * @param \Spicy\LyricsBundle\Entity\Lyrics $lyrics
     * @return Paragraph
     */
    public function setLyrics(\Spicy\LyricsBundle\Entity\Lyrics $lyrics = null)
    {
        $this->lyrics = $lyrics;

        return $this;
    }

    /**
     * Get lyrics
     *
     * @return \Spicy\LyricsBundle\Entity\Lyrics 
     */
    public function getLyrics()
    {
        return $this->lyrics;
    }
}
