<?php
namespace AppBundle\DataFixtures\ORM;
use       AppBundle\Entity\Booking\Survey\Survey;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\DataFixtures\DependentFixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadSurveyData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $batch     = 100;
        $dummy     = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sagittis turpis diam, vitae sagittis metus facilisis vitae. Vivamus nibh quam, tincidunt imperdiet elit nec, scelerisque pretium lacus. Mauris in massa ut lorem tincidunt vestibulum. Etiam nec mauris tincidunt, interdum justo bibendum, elementum leo. Sed vitae sem sit amet lacus porttitor hendrerit eget eget est. Duis at pharetra eros, sed congue lectus.';
        $lang      = ['nl', 'en', 'de'];
        $now       = new \DateTime('now');
        $next_week = clone $now;
        $next_week->add(new \DateInterval('P1W'));
        
        for ($i = 1; $i <= 500; $i++) {
            
            $language = $lang[rand(0, 2)];
            $survey   = new Survey();
            
            $survey->setBooking($this->getReference('booking-' . $i))
                   ->setType($this->getReference('type-' . $i))
                   ->setWebsiteText('[' . strtoupper($language) . '] ' . $dummy)
                   ->setWebsiteTextModified('[' . strtoupper($lang[rand(0, 2)]) . ']' . $dummy)
                   ->setWebsiteTextModifiedLanguage('[NL] ' . $dummy, 'nl')
                   ->setWebsiteTextModifiedLanguage('[EN] ' . $dummy, 'en')
                   ->setWebsiteTextModifiedLanguage('[DE] ' . $dummy, 'de')
                   ->setWebsiteName(($i % 2) === 0 ? 'Ibrahim' : '')
                   ->setReviewed(($i === 1 ? 1 : (rand(1,3))))
                   ->setLanguage($language)
                   ->setArrivalAt($now)
                   ->setDepartureAt($next_week)
                   ->setFilledInAt($now);
            
            $survey->setAnswers([
               
               1 => [
                   
                   1             => rand(1,10),
                   2             => rand(1, 10),
                   3             => rand(1, 10),
                   4             => rand(1, 10),
                   5             => rand(1, 10),
                   6             => rand(1, 10),
                   7             => rand(1, 10),
                   'explanation' => $dummy,
               ], 
               
               2 => [
                   
                   1       => rand(1, 10),
                   2       => rand(1, 10),
                   3       => rand(1, 10),
                   4       => rand(1, 10),
                   5       => rand(1, 10),
                   6       => rand(1, 10),
                   7       => rand(1, 10),
                   'other' => $dummy
               ],
               
               3 => [
                   
                   1             => rand(1, 10),
                   2             => rand(1, 10),
                   3             => rand(1, 10),
                   4             => rand(1, 10),
                   5             => rand(1, 10),
                   6             => rand(1, 10),
                   7             => rand(1, 10),
                   8             => rand(1, 10),
                   9             => rand(1, 10),
                   'explanation' => $dummy,
               ],
               
               4 => rand(1, 10),
               5 => [
                   
                   0             => rand(1, 10),
                   'explanation' => $dummy,
               ],
               6 => rand(1, 10),
               7 => rand(1, 10),
            ]);
            
            $manager->persist($survey);
            
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $manager->clear();
            }
        }
    }
    
    public function getDependencies()
    {
        return ['AppBundle\DataFixtures\ORM\LoadTypeData', 'AppBundle\DataFixtures\ORM\LoadBookingData'];
    }
}