<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Region\Region;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadRegionData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $batch           = 100;
        $websites        = ['C', 'W', 'E', 'T', 'B', 'V', 'Q', 'Z', 'N', 'I', 'K', 'X', 'Y'];
        $total_websites  = count($websites);
        $now             = new \DateTime('now');
        
        for ($i = 1; $i <= 500; $i++) {
            
            $region = new Region();
            $region->setLocaleNames([
                        
                        'nl' => 'NL Region #' . $i,
                        'en' => 'EN Region #' . $i,
                        'de' => 'DE Region #' . $i,
                   ])
                  ->setLocaleSeoNames([
    
                      'nl' => 'NL Seo Region #' . $i,
                      'en' => 'EN Seo Region #' . $i,
                      'de' => 'DE Seo Region #' . $i,
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
                   ->setAlternativeName('Alternative Region #' . $i)
                   ->setSeason($i % 2 === 0 ? 1 : 2)
                   ->setWebsites((array)array_rand(array_flip($websites), rand(1, $total_websites)))
                   ->setShortDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam amet.')
                   ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.')
                   ->setMinimumAltitude(rand(3000, 8000))
                   ->setMaximumAltitude(rand(8000, 12000))
                   ->setTotalSlopesDistance($i)
                   ->setCreatedAt($now)
                   ->setUpdatedAt($now);
            
            $manager->persist($region);
            $this->addReference('region-' . $i, $region);
            
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $manager->clear();
            }
        }
    }
}