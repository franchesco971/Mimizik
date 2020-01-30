<?php

namespace Spicy\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;

/**
 * Approval
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Spicy\SiteBundle\Repository\ApprovalRepository")
 */
class Approval
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
     * @ORM\Column(name="approvalDate", type="datetimetz", nullable=true)
     */
    private $approvalDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="disapprovalDate", type="datetimetz", nullable=true)
     */
    private $disapprovalDate;
    
    /**
     * @ORM\OneToOne(targetEntity="Spicy\SiteBundle\Entity\Title", cascade={"persist","merge"})
     * @ORM\JoinColumn(name="title_id", referencedColumnName="id")
     */
    private $title;
    
    /**
    * @ORM\ManyToOne(targetEntity="Spicy\UserBundle\Entity\User", inversedBy="approvals")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="requestDate", type="datetimetz")
     */
    private $requestDate;
    
    public function __construct() {
        $this->requestDate=new \DateTime;
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
     * Set approvalDate
     *
     * @param \DateTime $approvalDate
     *
     * @return Approval
     */
    public function setApprovalDate($approvalDate)
    {
        $this->approvalDate = $approvalDate;

        return $this;
    }

    /**
     * Get approvalDate
     *
     * @return \DateTime
     */
    public function getApprovalDate()
    {
        return $this->approvalDate;
    }

    /**
     * Set disapprovalDate
     *
     * @param \DateTime $disapprovalDate
     *
     * @return Approval
     */
    public function setDisapprovalDate($disapprovalDate)
    {
        $this->disapprovalDate = $disapprovalDate;

        return $this;
    }

    /**
     * Get disapprovalDate
     *
     * @return \DateTime
     */
    public function getDisapprovalDate()
    {
        return $this->disapprovalDate;
    }

    /**
     * Set title
     *
     * @param \Spicy\SiteBundle\Entity\Title $title
     *
     * @return Approval
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

    /**
     * Set user
     *
     * @param \Spicy\UserBundle\Entity\User $user
     *
     * @return Approval
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Spicy\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set requestDate
     *
     * @param \DateTime $requestDate
     *
     * @return Approval
     */
    public function setRequestDate($requestDate)
    {
        $this->requestDate = $requestDate;

        return $this;
    }

    /**
     * Get requestDate
     *
     * @return \DateTime
     */
    public function getRequestDate()
    {
        return $this->requestDate;
    }
}
