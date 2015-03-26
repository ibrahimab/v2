<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Type\Type;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\DataFixtures\DependentFixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadTypeData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $batch           = 100;
        $websites        = ['C', 'W', 'E', 'T', 'B', 'V', 'Q', 'Z', 'N', 'I', 'K', 'X', 'Y'];
        $total_websites  = count($websites);
        $now             = new \DateTime('now');
        
        for ($i = 1; $i <= 500; $i++) {
            
            $type = new Type();
            $type->setName('Type #' . $i)
                 ->setShortDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam amet.')
                 ->setInventory(rand(0, 10))
                 ->setWebsites((array)array_rand(array_flip($websites), rand(1, $total_websites)))
                 ->setCode('T' . str_pad($i, 5, '0', STR_PAD_LEFT))
                 ->setDisplay(true)
                 ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.')
                 ->setQuality(rand(1, 5))
                 ->setLatitude(52.076091)
                 ->setLongitude(4.892198)
                 ->setOptimalResidents(rand(6,12))
                 ->setMaxResidents(rand(13,20))
                 ->setCreatedAt($now)
                 ->setUpdatedAt($now)
                 ->setAccommodation($this->getReference('accommodation-' . $i));
            
            $manager->persist($type);
            $this->addReference('type-' . $i, $type);
            
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $manager->clear();
            }
        }
    }
    
    public function getDependencies()
    {
        return ['AppBundle\DataFixtures\ORM\LoadAccommodationData'];
    }
}