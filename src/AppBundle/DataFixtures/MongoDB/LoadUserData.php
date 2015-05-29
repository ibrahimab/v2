<?php
namespace AppBundle\DataFixtures\MongoDB;

use       AppBundle\Document\User\User;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\DataFixtures\DependentFixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadUserListingData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {   
        $now = new \DateTime();
		for ($i = 1; $i <= 500; $i++) {
            
			$user = new User();
			$user->setUserId('test_user_' . $i)
				 ->setFavorites(range(mt_rand(1, 20), mt_rand(21, 40)))
                 ->setViewed(range(mt_rand(41, 60), mt_rand(61, 80)))
                 ->setCreatedAt($now)
                 ->setUpdatedAt($now);

			$manager->persist($user);
		}

		$manager->flush();
	}
}