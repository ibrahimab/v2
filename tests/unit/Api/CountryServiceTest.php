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
    
    public function getPlaces()
    {
        $country = $this->countryService->find();
        $places = $country->getPlaces();
        
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Place\PlaceServiceEntityInterface', $places);
    }
}