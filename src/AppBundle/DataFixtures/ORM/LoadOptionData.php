<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Option\Group;
use       AppBundle\Entity\Option\Kind;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadOptionData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $batch = 100;
        $kinds = [];

        for ($i = 1; $i <= 500; $i++) {

            $kind = new Kind();
            $kind->setTravelInsurance(1);

            $manager->persist($kind);
            $this->addReference('kind-' . $i, $kind);

            if (($i % $batch) === 0) {

                $manager->flush();
                $manager->clear();
            }
        }

        for ($i = 1; $i <= 500; $i++) {

            $group = new Group();
            $group->setKind($this->getReference('kind-' . $i))
                  ->setName('Group #' . $i)
                  ->setDuration(rand(1, 100))
                  ->setLocaleDescriptions([
                      'nl' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                      'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                      'de' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                  ]);

            $manager->persist($group);

            if (($i % $batch) === 0) {

                $manager->flush();
                $manager->clear();
            }
        }
    }
}