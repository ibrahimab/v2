<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Accommodation\Accommodation;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadAccommodationData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $batch  = 100;
        for ($i = 1; $i <= 1000; $i++) {
            
            $accommodation = new Accommodation();
            $accommodation->setName('Accommodation #' . $i);
            
            $manager->persist($accommodation);
            $this->addReference('accommodation-' . $i, $accommodation);
            
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $manager->clear();
            }
        }
    }
}