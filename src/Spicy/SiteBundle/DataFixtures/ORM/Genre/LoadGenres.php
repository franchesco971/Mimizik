<?php 
namespace Spicy\SiteBundle\DataFixtures\ORM\Genre;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Spicy\SiteBundle\Entity\GenreMusical;

class LoadGenres implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $type=array(
            1=>'Dancehall',
            2=>'Rétro',
            3=>'Hip-hop',
            4=>'Zouk / Kizomba',
            5=>'Rap',
            6=>'Ragga',
            7=>'Bouyon',
            8=>'Reggae',
            9=>'Compas',
            10=>'Soca',
            11=>'Sega',
            12=>'Pop / R&B',
            13=>'Acoustique/Traditionel',
            14=>'Ambiance/Latino',
            15=>'Afro beat'
        );
        
        foreach ($type as $key => $value) {
            $genre=new GenreMusical();
            $genre->setId($key);
            $genre->setLibelle($value);
            
            $manager->persist($genre);
        }
        
        $manager->flush();
    }
}