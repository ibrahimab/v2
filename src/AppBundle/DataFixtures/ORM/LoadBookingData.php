<?php
namespace AppBundle\DataFixtures\ORM;

use       AppBundle\Entity\Booking\Booking;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\DataFixtures\DependentFixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadBookingData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $batch     = 100;
        $dummy     = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sagittis turpis diam, vitae sagittis metus facilisis vitae. Vivamus nibh quam, tincidunt imperdiet elit nec, scelerisque pretium lacus. Mauris in massa ut lorem tincidunt vestibulum. Etiam nec mauris tincidunt, interdum justo bibendum, elementum leo. Sed vitae sem sit amet lacus porttitor hendrerit eget eget est. Duis at pharetra eros, sed congue lectus.';
        $lang      = ['nl', 'en', 'de'];
        $websites  = ['C', 'W', 'E', 'T', 'B', 'V', 'Q', 'Z', 'N', 'I', 'K', 'X', 'Y'];
        $now       = new \DateTime('now');
        $next_week = clone $now;
        $next_week->add(new \DateInterval('P1W'));
        
        for ($i = 1; $i <= 500; $i++) {
            
            $language = $lang[rand(0, 2)];
            $booking  = new Booking();
            $type     = $this->getReference('type-' . $i);
            
            $booking->setType($type)
                    ->setAccommodationName($type->getAccommodation()->getName() . ' ' . $type->getLocaleName($language))
                    ->setBookingNumber($i)
                    ->setOldBookingNumber('')
                    ->setNewBookingNumber('')
                    ->setApproved(($i % 2 === 0))
                    ->setBlocked(($i % 5 === 0))
                    ->setCancelled(($i % 10 === 0))
                    ->setCancelledAt(($i % 10 === 0 ? $now : null))
                    ->setExpired(($i % 15 === 0))
                    ->setWebsite(array_rand(array_flip($websites)))
                    ->setLanguage($language)
                    ->setSeasonId(($i % 2 ? 1 : 2))
                    ->setPersons(rand(1, 20))
                    ->setDebitNumber($i)
                    ->setCountryId(rand(1, 7))
                    ->setSeen(($i % 20 === 0))
                    ->setEditedAt($now)
                    ->setArrivalAt($now->getTimestamp())
                    ->setExactArrivalAt($now->getTimestamp())
                    ->setExactDepartureAt($next_week->getTimestamp());
            
            $manager->persist($booking);
            $this->addReference('booking-' . $i, $booking);
            
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $manager->clear();
            }
        }
    }
    
    public function getDependencies()
    {
        return ['AppBundle\DataFixtures\ORM\LoadTypeData'];
    }
}