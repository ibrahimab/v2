<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Place\Place;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\DataFixtures\DependentFixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadPlaceData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $batch           = 100;
        $websites        = ['C', 'W', 'E', 'T', 'B', 'V', 'Q', 'Z', 'N', 'I', 'K', 'X', 'Y'];
        $total_websites  = count($websites);
        $now             = new \DateTime('now');
        $places          = [];
        $all             = [];
        
        for ($i = 1, $j = 1; $i <= 500; $i++) {
            
            $place = new Place();
            $place->setRegion($this->getReference('region-' . $i))
                  ->setCountry($this->getReference('country-' . $j))
                  ->setSeason(1)
                  ->setLocaleNames([
    
                      'nl' => 'NL Place #' . $i,
                      'en' => 'EN Place #' . $i,
                      'de' => 'DE Place #' . $i,
                  ])
                  ->setLocaleSeoNames([
    
                      'nl' => 'NL Seo Place #' . $i,
                      'en' => 'EN Seo Place #' . $i,
                      'de' => 'DE Seo Place #' . $i,
                  ])
                  ->setAlternativeName('Alt Place #' . $i)
                  ->setLocaleShortDescriptions([
                      
                      'nl' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam amet.',
                      'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam amet.',
                      'de' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam amet.',
                  ])
                  ->setLocaleDescriptions([
                      
                      'nl' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                      'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                      'de' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                  ])
                  ->setWebsites((array)array_rand(array_flip($websites), rand(1, $total_websites)))
                  ->setLatitude(52.076091)
                  ->setLongitude(4.892198)
                  ->setAltitude(rand(1000, 8000))
                  ->setCreatedAt($now)
                  ->setUpdatedAt($now);
            
            $manager->persist($place);
            $places[] = $place;
            $all[$i]  = $place;
            
            $this->addReference('place-' . $i, $place);
            
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $places = [];
            }
            
            $j = ($j === 10 ? 1 : ($j + 1));
        }
        
        $prev = null;
        foreach ($all as $key => $place2) {
        
            if ($key === 1) {
                $prev = $place2;
                continue;
            }
            
            $place2->setSibling($prev);
            $manager->persist($place2);
            
            $prev = $place2;
        }
        
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return ['AppBundle\DataFixtures\ORM\LoadRegionData', 'AppBundle\DataFixtures\ORM\LoadCountryData'];
    }
}