<?php
namespace Api;


class CountryServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->countryService   = $this->serviceContainer->get('service.api.country');
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
}