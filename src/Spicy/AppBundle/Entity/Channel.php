<?php

namespace Spicy\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Channel
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Channel
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="channelId", type="string", length=255)
     */
    private $channelId;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalItemCount", type="integer")
     */
    private $totalItemCount;
    
    public function __construct() {
        $this->totalItemCount = 0;
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
     * Set title
     *
     * @param string $title
     *
     * @return Channel
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

    /**
     * Set channelId
     *
     * @param string $channelId
     *
     * @return Channel
     */
    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * Get channelId
     *
     * @return string
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * Set totalItemCount
     *
     * @param integer $totalItemCount
     *
     * @return Channel
     */
    public function setTotalItemCount($totalItemCount)
    {
        $this->totalItemCount = $totalItemCount;

        return $this;
    }

    /**
     * Get totalItemCount
     *
     * @return integer
     */
    public function getTotalItemCount()
    {
        return $this->totalItemCount;
    }
}

