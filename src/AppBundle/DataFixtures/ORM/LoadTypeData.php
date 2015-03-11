<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Type\Type;
use       Doctrine\Common\DataFixtures\FixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadTypeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $batch           = 100;
        $websites        = ['C', 'W', 'E', 'T', 'B', 'V', 'Q', 'Z', 'N', 'I', 'K', 'X', 'Y'];
        $total_websites  = count($websites);
        $now             = new \DateTime('now');
        
        for ($i = 1; $i <= 1000; $i++) {
            
            $type = new Type();
            $type->setAccommodationId($i)
                 ->setName('Type #' . $i)
                 ->setShortDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam amet.')
                 ->setInventory(rand(0, 10))
                 ->setWebsites((array)array_rand(array_flip($websites), rand(1, $total_websites)))
                 ->setCode('T' . str_pad($i, 5, '0', STR_PAD_LEFT))
                 ->setDisplay(($i % 2 === 0))
                 ->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.')
                 ->setLatitude(52.076091)
                 ->setLongitude(4.892198)
                 ->setCreatedAt($now)
                 ->setUpdatedAt($now);
            
            $manager->persist($type);
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $manager->clear();
            }
        }
    }
}