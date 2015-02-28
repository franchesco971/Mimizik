<?php

namespace Spicy\RankingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * VideoRanking
 *
 * @ORM\Table(name="video_ranking")
 * @ORM\Entity(repositoryClass="Spicy\RankingBundle\Entity\Repository\VideoRankingRepository")
 */
class VideoRanking
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
     * @var integer
     * 
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_vu", type="integer")
     */
    private $nbVu;
    
    /**
    * @ORM\ManyToOne(targetEntity="Spicy\SiteBundle\Entity\Video",inversedBy="videoRankings")
    * @ORM\JoinColumn(name="video_id", referencedColumnName="id")
    * @Assert\Valid()
    */
    private $video;
    
    /**
    * @ORM\ManyToOne(targetEntity="Spicy\RankingBundle\Entity\Ranking",inversedBy="videoRankings")
    * @ORM\JoinColumn(name="ranking_id", referencedColumnName="id")
    * @Assert\Valid()
    */
    private $ranking;
    
    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255, nullable=true)
     */
    private $icon;

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
     * @return VideoRanking
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
     * Set nbVu
     *
     * @param integer $nbVu
     * @return VideoRanking
     */
    public function setNbVu($nbVu)
    {
        $this->nbVu = $nbVu;
    
        return $this;
    }

    /**
     * Get nbVu
     *
     * @return integer 
     */
    public function getNbVu()
    {
        return $this->nbVu;
    }


    /**
     * Set video
     *
     * @param \Spicy\SiteBundle\Entity\Video $video
     * @return VideoRanking
     */
    public function setVideo(\Spicy\SiteBundle\Entity\Video $video = null)
    {
        $this->video = $video;
    
        return $this;
    }

    /**
     * Get video
     *
     * @return \Spicy\SiteBundle\Entity\Video 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set ranking
     *
     * @param \Spicy\RankingBundle\Entity\Ranking $ranking
     * @return VideoRanking
     */
    public function setRanking(\Spicy\RankingBundle\Entity\Ranking $ranking = null)
    {
        $this->ranking = $ranking;
    
        return $this;
    }

    /**
     * Get ranking
     *
     * @return \Spicy\RankingBundle\Entity\Ranking 
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return VideoRanking
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    
        return $this;
    }

    /**
     * Get icon
     *
     * @return string 
     */
    public function getIcon()
    {
        return $this->icon;
    }
}