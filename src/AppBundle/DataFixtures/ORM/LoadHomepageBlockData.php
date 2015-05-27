<?php
namespace AppBundle\DataFixtures\ORM;

use       AppBundle\Entity\HomepageBlock\HomepageBlock;
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
class LoadHomepageBlockData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $batch          = 100;
        $displayed      = 1;
        $display_limit  = 3;
        $rank           = 0;
        $websites       = ['C', 'W', 'E', 'T', 'B', 'V', 'Q', 'Z', 'N', 'I', 'K', 'X', 'Y'];
        $total_websites = count($websites);
        $current_rank   = 0;
        $now            = new \DateTime('now');
        $published      = clone $now;
        $published->sub(new \DateInterval('P1D'));
        $expired        = clone $now;
        $expired->add(new \DateInterval('P1Y'));

        for ($i = 1; $i <= 500; $i++)
        {
            $rand           = rand(1, $total_websites);
            $randomWebsites = (array)array_rand(array_flip($websites), $rand);
            
            $display = false;
            if ((($i % 2) === 0) && $displayed <= $display_limit) {
                
                $display    = true;
                $rank      += 1;
                $displayed += 1;
            }
            
            $highlight = new HomepageBlock();
            $highlight->setDisplay($display)
                      ->setSeason((($i % rand(1,2)) === 0 ? 1 : 2))
                      ->setWebsites($randomWebsites)
                      ->setRank($rank)
                      ->setPosition($rank)
                      ->setLink('http://www.google.nl')
                      ->setLocaleTitles([
    
                          'nl' => 'NL Title Homepage block #' . $i,
                          'en' => 'EN Title Homepage block #' . $i,
                          'de' => 'DE Title Homepage block #' . $i,
                      ])
                      ->setLocaleButtons([
    
                          'nl' => 'NL Button Homepage block #' . $i,
                          'en' => 'EN Button Homepage block #' . $i,
                          'de' => 'DE Button Homepage block #' . $i,
                      ])
                      ->setPublishedAt(($display === true ? $published : null))
                      ->setExpiredAt(($display === true ? $expired : null))
                      ->setCreatedAt($now)
                      ->setUpdatedAt($now);
            
            $manager->persist($highlight);
            
            if (($i % $batch) === 0) {
                
                $manager->flush();
                $manager->clear();
            }
        }
    }
}