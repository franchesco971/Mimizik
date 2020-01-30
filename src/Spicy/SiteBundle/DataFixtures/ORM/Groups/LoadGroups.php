<?php

namespace Spicy\SiteBundle\DataFixtures\ORM\Groups;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Spicy\UserBundle\Entity\Group;

class LoadGroups implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $groups=['Utilisateur'=>['ROLE_USER'],'Contributeur'=>['ROLE_CONTRIBUTOR','ROLE_USER']];
        
        foreach ($groups as $groupName => $roles) {
            $group=new Group($groupName);
            $group->setRoles($roles);
            
            $manager->persist($group);
        }
        
        $manager->flush();
    }
}