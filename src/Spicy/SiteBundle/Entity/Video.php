<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Spicy\SiteBundle\Entity\Title;
use JMS\Serializer\Annotation as Serializer;

/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="Spicy\SiteBundle\Entity\VideoRepository")
 * @Serializer\ExclusionPolicy("none")
 */
class Video extends Title
{
    /**
    * @ORM\ManyToMany(targetEntity="Spicy\SiteBundle\Entity\TypeVideo", cascade={"persist"})
    * @Assert\Valid()
    * @Serializer\Expose
    */
    private $type_videos;
    
        
    /**
    * @ORM\OneToMany(targetEntity="Spicy\RankingBundle\Entity\VideoRanking", mappedBy="video")
    * @Assert\Valid()
    * @Serializer\Exclude
    */
    private $videoRankings;
    
    /**
     * Add type_videos
     *
     * @param \Spicy\SiteBundle\Entity\TypeVideo $typeVideos
     * @return Video
     */
    public function addTypeVideo(\Spicy\SiteBundle\Entity\TypeVideo $typeVideos)
    {
        $this->type_videos[] = $typeVideos;
    
        return $this;
    }

    /**
     * Remove type_videos
     *
     * @param \Spicy\SiteBundle\Entity\TypeVideo $typeVideos
     */
    public function removeTypeVideo(\Spicy\SiteBundle\Entity\TypeVideo $typeVideos)
    {
        $this->type_videos->removeElement($typeVideos);
    }

    /**
     * Get type_videos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypeVideos()
    {
        return $this->type_videos;
    }
    
    /**
     * Add videoRankings
     *
     * @param \Spicy\RankingBundle\Entity\VideoRanking $videoRankings
     * @return Video
     */
    public function addVideoRanking(\Spicy\RankingBundle\Entity\VideoRanking $videoRankings)
    {
        $this->videoRankings[] = $videoRankings;
    
        return $this;
    }

    /**
     * Remove videoRankings
     *
     * @param \Spicy\RankingBundle\Entity\VideoRanking $videoRankings
     */
    public function removeVideoRanking(\Spicy\RankingBundle\Entity\VideoRanking $videoRankings)
    {
        $this->videoRankings->removeElement($videoRankings);
    }

    /**
     * Get videoRankings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVideoRankings()
    {
        return $this->videoRankings;
    }
    
    public function getNomTypes()
    {
        $noms='';
        
        if(count($this->type_videos))
        {
            foreach ($this->type_videos as $key => $type) {
                $noms=$noms.$type->getLibelle();
                                
                if($key!=count($this->type_videos)-1)
                {
                    $noms=$noms.', ';
                }
            }
        }
        else{
            $noms='Inconnu';
        }
        
        return $noms;
    }
    
}
