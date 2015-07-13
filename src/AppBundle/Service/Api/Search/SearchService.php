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
    public function search()
    {
        return $this->searchServiceRepository->search($this->build());
    }
    
    public function findOnlyNames($countries, $regions, $places, $accommodations, $types)
    {
        return $this->searchServiceRepository->findOnlyNames($countries, $regions, $places, $accommodations, $types);
    }
    
    public function facets(PaginatorService $paginator, $filters)
    {
        return $this->searchServiceRepository->facets($paginator, $filters);
    }
}