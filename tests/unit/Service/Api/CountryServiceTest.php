<?php
namespace AppBundle\Tests\Unit\Service\Api;

use       AppBundle\Entity\Country\Country;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Concern\SeasonConcern;

class CountryServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var \appTestDebugProjectContainer
     */
    protected $serviceContainer;
    
    /**
     * @var \AppBundle\Service\Api\Country\CountryService
     */
    protected $countryContainer;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->countryService   = $this->serviceContainer->get('service.api.country');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->countryService = null;
    }

    public function testGetCountries()
    {
        $limit     = 5;
        $countries = $this->countryService->all(['limit' => $limit]);

        $this->assertInternalType('array', $countries);
        $this->assertCount($limit, $countries);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $countries);
    }

    public function testGetCountry()
    {
        $country = $this->countryService->find();

        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $country);
    }

    public function testNotFoundCountry()
    {
        $country = $this->countryService->find(['id' => 'non-existant']);

        $this->assertNull($country);
    }
    
    public function testGetPlaces()
    {
        $country = $this->countryService->find();
        $places = $country->getPlaces();
        
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $places);
    }
    
    public function testGetCountryByLocaleName()
    {
        $country = $this->countryService->find();
        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $country);

        $localeCountryNL = $this->countryService->findByLocaleName($country->getName(), 'nl');
        $localeCountryEN = $this->countryService->findByLocaleName($country->getEnglishName(), 'en');
        $localeCountryDE = $this->countryService->findByLocaleName($country->getGermanName(), 'de');
        
        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $localeCountryNL, 'Dutch variant not found');
        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $localeCountryEN, 'English variant not found');
        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $localeCountryDE, 'German variant not found');
        
        $this->assertEquals($country->getId(), $localeCountryNL->getId());
        $this->assertEquals($country->getId(), $localeCountryEN->getId());
        $this->assertEquals($country->getId(), $localeCountryDE->getId());
    }
    
    public function testGetRegionsByCountry()
    {
        $countryId = 5;
        $country   = new Country($countryId);
        $this->assertEquals($countryId, $country->getId());
        
        $country   = $this->countryService->findRegions($country);
        $places    = $country->getPlaces();
        $regions   = $places->map(function(PlaceServiceEntityInterface $place) { return $place->getRegion(); });
        
        $this->assertInstanceOf('AppBundle\Service\Api\Country\CountryServiceEntityInterface', $country);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $places);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Region\RegionServiceEntityInterface', $regions);
    }
}