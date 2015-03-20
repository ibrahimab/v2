<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Country\Country;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadCountryData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $now = new \DateTime('now');
        
        for ($i = 1; $i <= 10; $i++) {
            
            $country = new Country();
            $country->setLocaleNames([
                        
                        'nl' => 'NL Country #' . $i,
                        'en' => 'EN Country #' . $i,
                        'de' => 'DE Country #' . $i,
                    ])
                    ->setStartCode('C' . $i)
                    ->setAlternativeName('Alternative Country #' . $i)
                    ->setDisplay(true)
                    ->setLocaleTitles([
                        
                        'nl' => 'Country Title #' . $i,
                        'en' => 'Country Title #' . $i,
                        'de' => 'Country Title #' . $i,
                    ])
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
                    ->setColourCode($i)
                    ->setAccommodationCodes(['F000' . $i, 'F000' . ($i + 1), 'F000' . ($i + 2), 'F000' . ($i + 3)])
                    ->setCreatedAt($now)
                    ->setUpdatedAt($now);
            
            $manager->persist($country);
            $this->addReference('country-' . $i, $country);
        }
        
        $manager->flush();
        $manager->clear();
    }
}