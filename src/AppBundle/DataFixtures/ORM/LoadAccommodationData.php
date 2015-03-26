<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Accommodation\Accommodation;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\DataFixtures\DependentFixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadAccommodationData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $batch  = 100;
        $kinds  = range(1, 12);
        unset($kinds[array_search(5, $kinds)]);
        
        for ($i = 1; $i <= 500; $i++) {
            
            $accommodation = new Accommodation();
            $accommodation->setName('Accommodation #' . $i)
                          ->setPlace($this->getReference('place-' . $i))
                          ->setKind($kinds[array_rand($kinds)])
                          ->setDisplay(true)
                          ->setWeekendSki(false);
            
            $manager->persist($accommodation);
            $this->addReference('accommodation-' . $i, $accommodation);
            
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $manager->clear();
            }
        }
    }
    
    public function getDependencies()
    {
        return ['AppBundle\DataFixtures\ORM\LoadPlaceData'];
    }
}