<?php
namespace AppBundle\Service\Api\Search;
use       AppBundle\Service\Api\Search\Result\Resultset;
use       AppBundle\Service\FilterService;
use       AppBundle\Service\FacetService;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\AppTrait\LocaleTrait;

/**
 * This is the search service
 *
 * This is a fluent interface to build your search in a programmatically
 * way. It will give you a list of type Ids when called to give you results
 */
class SearchService
{
    use LocaleTrait;

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
        $builder   = $this->build();
        $resultset = new Resultset($this->searchServiceRepository->search($builder), $this->builder);
        $resultset->setLocale($this->getLocale());
        $resultset->paginator()->setLimit($builder->block(SearchBuilder::BLOCK_LIMIT));
        $resultset->paginator()->setCurrentPage($builder->block(SearchBuilder::BLOCK_PAGE));
        $resultset->prepare();

        return $resultset;
    }

    /**
     * @return integer
     */
    public function count()
    {
        return $this->searchServiceRepository->count($this->build());
    }

    /**
     * @param array $countries
     * @param array $regions
     * @param array $places
     * @param array $accommodations
     * @param array $types
     */
    public function findOnlyNames($countries, $regions, $places, $accommodations, $types)
    {
        return $this->searchServiceRepository->findOnlyNames($countries, $regions, $places, $accommodations, $types);
    }

    /**
     * @param PaginatorService $paginator
     * @param array $filters
     */
    public function facets(Resultset $resultset, $filters)
    {
        $facetService = new FacetService($resultset, $filters);
        $facetService->calculate();

        return $facetService;
    }
}
