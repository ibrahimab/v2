<?php
namespace AppBundle\Tests\Unit\Service\Api;
use       AppBundle\Service\Api\Search\SearchService;

class SearchServiceTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    /**
     * @var SearchService
     */
    protected $searchService;

    protected function _before()
    {
        $this->serviceContainer = $this->getModule('Symfony2')->container;
        $this->searchService    = $this->serviceContainer->get('service.api.search');
        
        // clearing doctrine
        $this->serviceContainer->get('doctrine')->getManager()->clear();
    }
    
    protected function _after()
    {
        $this->searchService = null;
    }
    
    public function testGetDefaultResults()
    {
        $resultsPerPage = $this->serviceContainer->getParameter('app')['results_per_page'];
        $offset         = 0;        
        $results        = $this->searchService->build()->limit($resultsPerPage)->offset($offset)->results();

        $this->assertContainsOnlyInstancesOf('AppBundle\Service\Api\Type\TypeServiceEntityInterface', $results);
        // $this->assertCount($resultsPerPage, $results);
    }   
}