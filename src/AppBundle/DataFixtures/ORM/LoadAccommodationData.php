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
        $now    = new \DateTime('now');
        unset($kinds[array_search(5, $kinds)]);

        for ($i = 1; $i <= 500; $i++) {

            $accommodation = new Accommodation();
            $accommodation->setLocaleNames([

                              'nl' => 'NL Accommodation #' . $i,
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
                          ->setLocaleLayouts([

                              'nl' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                              'en' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                              'de' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor nisi ligula, molestie facilisis lorem fringilla sit amet. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent euismod eros ut elementum ultricies. Nam nec ipsum ac turpis tempus eleifend vitae in est. Donec sed sapien orci. Suspendisse scelerisque sed quam a sagittis. Pellentesque nibh quam, semper in viverra at, sodales in risus. Sed vitae arcu erat. Sed sagittis gravida egestas. Nam sed dolor rhoncus, gravida ex non, euismod nisi. Donec quis nunc eu quam convallis maximus accumsan ac dui. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.',
                          ])
                          ->setFeatures(((array)(array_rand(array_flip(range(1, 24)), rand(1, rand(1, 24))))))
                          ->setPlace($this->getReference('place-' . $i))
                          ->setKind($kinds[array_rand($kinds)])
                          ->setDisplay(true)
                          ->setShow(1)
						  ->setDisplaySearch(true)
                          ->setQuality(rand(1, 5))
                          ->setWeekendSki(($i === 1 ? false : ($i % 2 === 0)))
                          ->setSeason(1)
                          ->setSearchOrder($i)
                          ->setCreatedAt($now)
                          ->setUpdatedAt($now);

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