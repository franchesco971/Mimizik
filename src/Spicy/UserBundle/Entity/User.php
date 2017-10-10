<?php

namespace Spicy\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use FOS\UserBundle\Model\GroupInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Spicy\UserBundle\Entity\Group;

/**
 * User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="Spicy\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    private $facebookId;
    
    /**
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    private $googleId;

    private $facebookAccessToken;
    
    private $googleAccessToken;
    
    /**
     * @ORM\ManyToMany(targetEntity="Spicy\UserBundle\Entity\Group")
     * @ORM\JoinTable(name="user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    
    /**
    * @ORM\OneToMany(targetEntity="Spicy\SiteBundle\Entity\Approval", mappedBy="user")
    */
    private $approvals;
    
    public function __construct() {
        parent::__construct();
        $this->groups== new ArrayCollection();
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }
    
        /**
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }
    
        /**
     * @param string $googleAccessToken
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }
    
    // Note le singulier, on ajoute une seule catégorie à la fois
    public function addGroups(GroupInterface $group)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->groups[] = $group;

        return $this;
    }
    
    public function addGroup(GroupInterface $group)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->groups[] = $group;

        return $this;
    }

    public function removeGroup(GroupInterface $group)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer le group en argument
        $this->groups->removeElement($group);
    }

    // Note le pluriel, on récupère une liste de groups ici !
    public function getGroups()
    {
        return $this->groups;
    }
    
    public function setGroups(GroupInterface $group) {
        $this->addGroup($group);
    }

    /**
     * Add approuval
     *
     * @param \Spicy\SiteBundle\Entity\Approval $approval
     *
     * @return User
     */
    public function addApproval(\Spicy\SiteBundle\Entity\Approval $approval)
    {
        $this->approuvals[] = $approval;

        return $this;
    }

    /**
     * Remove approuval
     *
     * @param \Spicy\SiteBundle\Entity\Approval $approval
     */
    public function removeApproval(\Spicy\SiteBundle\Entity\Approval $approval)
    {
        $this->approuvals->removeElement($approval);
    }

    /**
     * Get approuvals
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApprovals()
    {
        return $this->approvals;
    }
}
