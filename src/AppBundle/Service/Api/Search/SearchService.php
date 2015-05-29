<?php
namespace AppBundle\Service\Api\Search;

/**
 * This is the search service
 *
 * This is a fluent interface to build your search in a programmatically
 * way. It will give you a list of type Ids when called to give you results
 */
class SearchService
{
    /**
     * @var SearchServiceRepositoryInterface
     */
    private $searchServiceRepository;
    
    /**
     * @var array
     */
    private $builder;
    
    /**
     * Constructor
     *
     * @param SearchServiceRepositoryInterface $searchServiceRepository
     */
    public function __construct(SearchServiceRepositoryInterface $searchServiceRepository)
    {
        $this->searchServiceRepository = $searchServiceRepository;
        $this->builder                 = new SearchBuilder($this);
    }
    
    /**
     * @return SearchBuilder
     */
    public function build()
    {
        return $this->builder;
    }
    
    /**
     * @return array
     */
    public function results()
    {
        return $this->searchServiceRepository->search($this->build());
    }
}