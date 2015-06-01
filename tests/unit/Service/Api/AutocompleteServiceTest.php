<?php
namespace AppBundle\Tests\Unit\Service\Api;
use       AppBundle\Service\Api\AutocompleteService;

class AutocompleteServiceTest extends \Codeception\TestCase\Test
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
     * @var \AppBundle\Service\Api\AutocompleteService
     */
    protected $autocompleteService;

    protected function _before()
    {
        $this->serviceContainer    = $this->getModule('Symfony2')->container;
        $this->autocompleteService = $this->serviceContainer->get('service.api.autocomplete');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->serviceContainer    = null;
        $this->autocompleteService = null;
    }

    public function testAutocompleteOnlyAccommodations()
    {
        $results = $this->autocompleteService->search('Accommodation', [AutocompleteService::ACCOMMODATION]);
        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Autocomplete\AutocompleteServiceDocumentInterface', $results);
    }
}