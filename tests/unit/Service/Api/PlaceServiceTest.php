<?php
namespace AppBundle\Tests\Unit\Service\Api;

use       AppBundle\Concern\SeasonConcern;

class PlaceServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->placeService     = $this->serviceContainer->get('service.api.place');
        $this->regionService    = $this->serviceContainer->get('service.api.region');

        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }

    protected function _after()
    {
        $this->placeService = null;
    }

    public function testGetPlaces()
    {
        $limit  = 5;
        $places = $this->placeService->all(['limit' => $limit]);

        $this->assertInternalType('array', $places);
        $this->assertCount($limit, $places);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $places);
    }

    public function testGetPlace()
    {
        $place = $this->placeService->find();

        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);
    }

    public function testNotFoundPlace()
    {
        $place = $this->placeService->find(['id' => 'non-existant']);

        $this->assertNull($place);
    }

    public function testGetPlaceAndSibling()
    {
        // getting place
        $place = $this->placeService->find(['id' => 2]);

        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);

        // getting sibling
        $sibling = $place->getSibling();

        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $sibling);
    }

    public function testGetPlaceWithoutSibling()
    {
        // getting place
        $place = $this->placeService->find(['id' => 1]);

        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);

        // sibling has to be null
        $sibling = $place->getSibling();
        $this->assertNull($sibling);
    }

    public function testGetRegionFromPlace()
    {
        // getting place
        $place = $this->placeService->find(['id' => 1]);

        // getting region
        $region = $place->getRegion();

        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $region);
    }

    public function testGetAccommodations()
    {
        $place = $this->placeService->find();
        $accommodations = $place->getAccommodations();

        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface', $accommodations);
    }

    public function testGetCountry()
    {
        $place = $this->placeService->find();
        $country = $place->getCountry();

        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $country);
    }

    public function testGetPlaceByLocaleSeoName()
    {
        $place = $this->placeService->find(['season' => SeasonConcern::SEASON_WINTER]);
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $place);

        $otherPlace = $this->placeService->findByLocaleSeoName($place->getSeoName(), 'nl');
        $this->assertInstanceOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $otherPlace);
    }

    public function testGetHomepagePlaces()
    {
        $regions = $this->regionService->findHomepageRegions(['limit' => 1]);
		$places  = $this->placeService->findHomepagePlaces($regions[0], ['limit' => 1]);

        $this->assertInstanceOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $regions[0]);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $places);
    }
}