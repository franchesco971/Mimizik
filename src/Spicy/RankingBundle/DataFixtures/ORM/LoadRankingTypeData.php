<?php 

namespace Spicy\RankingBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Spicy\RankingBundle\Entity\RankingType;

class LoadRankingTypeData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $type = array(1 => 'JOUR', 2 => 'MOIS', 3 => 'ANNEE');

        foreach ($type as $key => $value) {
            $type = new RankingType();
            $type->setId($key);
            $type->setName($value);

            $manager->persist($type);
        }

        $manager->flush();
    }
}