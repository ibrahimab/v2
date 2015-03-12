<?php
namespace AppBundle\DataFixtures\ORM;

use       AppBundle\Entity\Type\Type;
use       AppBundle\Entity\Highlight\Highlight;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\DataFixtures\DependentFixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadHighlightData
 * 
 * This class seeds the database with highlights for testing
 * 
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class LoadHighlightData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $batch          = 100;
        $displayed      = 1;
        $display_limit  = 6;
        $rank           = 0;
        $websites       = ['C', 'W', 'E', 'T', 'B', 'V', 'Q', 'Z', 'N', 'I', 'K', 'X', 'Y'];
        $total_websites = count($websites);
        $current_rank   = 0;
        $now            = new \DateTime('now');
        
        $two_days_ago   = clone $now;
        $two_days_ago->sub(new \DateInterval('P2D'));
        
        $next_year      = clone $now;
        $next_year->add(new \DateInterval('P1Y'));
        
        $yesterday      = clone $now;
        $yesterday->sub(new \DateInterval('P1D'));

        for ($i = 1; $i <= 1000; $i++)
        {
            $rand           = rand(1, $total_websites);
            $randomWebsites = (array)array_rand(array_flip($websites), $rand);
            
            $display = false;
            if ((($i % 2) === 0) && $displayed <= $display_limit) {
                
                $display    = true;
                $rank      += 1;
                $displayed += 1;
            }
            
            $highlight = new Highlight();
            $highlight->setDisplay($display)
                      ->setSeason((($i % rand(1,2)) === 0 ? 1 : 2))
                      ->setWebsites($randomWebsites)
                      ->setRank($display === true ? $rank : null)
                      ->setPublishedAt(($display === true ? ($rank === 1 ? $two_days_ago : $now) : null))
                      ->setExpiredAt(($display === true ? ($rank === 1 ? $yesterday : $next_year) : null))
                      ->setCreatedAt($now)
                      ->setUpdatedAt($now)
                      ->setType($this->getReference('type-' . $i));
            
            $manager->persist($highlight);
            
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