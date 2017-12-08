<?php

namespace Spicy\ITWBundle\Entity\Repository;

use Spicy\SiteBundle\Entity\Artiste;

class InterviewRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLastByArtiste(Artiste $artiste)
    {
        $qb = $this->createQueryBuilder('i')
                ->where('i.artiste = :artiste')
                ->having('i.createdAt = (select max(itw.createdAt) from SpicyITWBundle:Interview itw where itw.artiste=:artiste and itw.active = :active)')
                ->setParameter('active', 1)
                ->setParameter('artiste', $artiste);
        
        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }
}
