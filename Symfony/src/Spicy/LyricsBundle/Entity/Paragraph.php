<?php

namespace Spicy\LyricsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="content", type="text")
     */
    private $content;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="paragraph_type", type="integer")
     */
    private $paragraph_type;


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
     * Set content
     *
     * @param string $content
     * @return Paragraph
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
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
}
