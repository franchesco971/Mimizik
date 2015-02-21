<?php

namespace Spicy\RankingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ranking
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Spicy\RankingBundle\Entity\Repository\RankingRepository")
 */
class Ranking
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
     * @ORM\Column(name="date_ranking", type="datetime")
     */
    private $dateRanking;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_ranking", type="datetime")
     */
    private $startRanking;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_ranking", type="datetime")
     */
    private $endRanking;
    
    /**
    * @ORM\OneToMany(targetEntity="Spicy\SiteBundle\Entity\Video", mappedBy="ranking")
     * @Assert\Valid()
    */
    private $videos;
    
    /**
    * @ORM\ManyToOne(targetEntity="Spicy\RankingBundle\Entity\RankingType")
     * @Assert\Valid()
    */
    private $rankingType;

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
     * Set rankingType
     *
     * @param \Spicy\RankingBundle\Entity\RankingType $rankingType
     * @return Ranking
     */
    public function setRankingType(\Spicy\RankingBundle\Entity\RankingType $rankingType = null)
    {
        $this->rankingType = $rankingType;
    
        return $this;
    }

    /**
     * Get rankingType
     *
     * @return \Spicy\RankingBundle\Entity\RankingType 
     */
    public function getRankingType()
    {
        return $this->rankingType;
    }

    /**
     * Set startRanking
     *
     * @param \DateTime $startRanking
     * @return Ranking
     */
    public function setStartRanking($startRanking)
    {
        $this->startRanking = $startRanking;
    
        return $this;
    }

    /**
     * Get startRanking
     *
     * @return \DateTime 
     */
    public function getStartRanking()
    {
        return $this->startRanking;
    }

    /**
     * Set endRanking
     *
     * @param \DateTime $endRanking
     * @return Ranking
     */
    public function setEndRanking($endRanking)
    {
        $this->endRanking = $endRanking;
    
        return $this;
    }

    /**
     * Get endRanking
     *
     * @return \DateTime 
     */
    public function getEndRanking()
    {
        return $this->endRanking;
    }

    /**
     * Set dateRanking
     *
     * @param \DateTime $dateRanking
     * @return Ranking
     */
    public function setDateRanking($dateRanking)
    {
        $this->dateRanking = $dateRanking;
    
        return $this;
    }

    /**
     * Get dateRanking
     *
     * @return \DateTime 
     */
    public function getDateRanking()
    {
        return $this->dateRanking;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add videos
     *
     * @param \Spicy\SiteBundle\Entity\Video $videos
     * @return Ranking
     */
    public function addVideo(\Spicy\SiteBundle\Entity\Video $videos)
    {
        $this->videos[] = $videos;
    
        return $this;
    }

    /**
     * Remove videos
     *
     * @param \Spicy\SiteBundle\Entity\Video $videos
     */
    public function removeVideo(\Spicy\SiteBundle\Entity\Video $videos)
    {
        $this->videos->removeElement($videos);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideos()
    {
        return $this->videos;
    }
}