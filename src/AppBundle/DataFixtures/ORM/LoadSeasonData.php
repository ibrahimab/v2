<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Season\Season;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadSeasonData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $batch = 100;
        $start = new \DateTime('now');
        $end   = clone $start;
        $end->add(new \DateInterval('P1Y'));

        for ($i = 1; $i <= 500; $i++) {

            $season = new Season();
            $season->setLocaleNames([

                       'nl' => 'NL Season #' . $i,
                       'en' => 'EN Season #' . $i,
                       'de' => 'DE Season #' . $i,
                   ])
                   ->setDisplay(true)
                   ->setSeason(rand(1, 2))
                   ->setInsurancesPolicyCosts(rand(1, 100))
                   ->setStart($start)
                   ->setEnd($end);

            $manager->persist($season);

            if (($i % $batch) === 0) {

                $manager->flush();
                $manager->clear();
            }
        }
    }
}