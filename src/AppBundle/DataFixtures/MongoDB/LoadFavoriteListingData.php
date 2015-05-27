<?php
namespace AppBundle\DataFixtures\MongoDB;

use       AppBundle\Document\Listing\Favorite;
use       Doctrine\Common\DataFixtures\AbstractFixture;
use       Doctrine\Common\DataFixtures\DependentFixtureInterface;
use       Doctrine\Common\Persistence\ObjectManager;

class LoadFavoriteListingData extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
		for ($i = 1; $i <= 500; $i++) {

			$favoriteListing = new Favorite();
			$favoriteListing->setUserId('test_user')
							->setType($i);

			$manager->persist($favoriteListing);
		}

		$manager->flush();
	}
}