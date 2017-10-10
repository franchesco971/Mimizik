<?php

namespace Spicy\ITWBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Interview
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Interview
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
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;
    
    /**
    * @ORM\ManyToOne(targetEntity="Spicy\SiteBundle\Entity\Artiste",inversedBy="interviews")
     * @Assert\Valid()
    */
    private $artiste;
    
    /**
    * @ORM\OneToMany(targetEntity="Spicy\ITWBundle\Entity\Question", mappedBy="interview")
    * @Assert\Valid()
    */
    private $questions;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;


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
     * @return Interview
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
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set artiste
     *
     * @param \Spicy\SiteBundle\Entity\Artiste $artiste
     * @return Interview
     */
    public function setArtiste(\Spicy\SiteBundle\Entity\Artiste $artiste = null)
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * Get artiste
     *
     * @return \Spicy\SiteBundle\Entity\Artiste 
     */
    public function getArtiste()
    {
        return $this->artiste;
    }

    /**
     * Add questions
     *
     * @param \Spicy\ITWBundle\Entity\Question $questions
     * @return Interview
     */
    public function addQuestion(\Spicy\ITWBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Spicy\ITWBundle\Entity\Question $questions
     */
    public function removeQuestion(\Spicy\ITWBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Interview
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
}
