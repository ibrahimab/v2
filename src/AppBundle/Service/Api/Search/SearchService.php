<?php
namespace AppBundle\Service\Api\Search;

use AppBundle\Service\Api\Search\Builder\Builder as SearchBuilder;
use AppBundle\Service\Api\Search\Filter\Builder  as FilterBuilder;
use AppBundle\Service\Api\Search\Builder\Sort;
use AppBundle\Service\Api\Search\Builder\Where;
use AppBundle\Service\Api\Search\Result\Resultset;
use AppBundle\Service\Api\Search\Result\Paginator\Paginator;
use AppBundle\Service\Api\Booking\Survey\SurveyService;
use AppBundle\Service\Api\Search\FacetService;
use AppBundle\Service\Api\Search\Repository\RepositoryInterface;
use AppBundle\Service\Api\Search\Repository\PriceRepositoryInterface;
use AppBundle\Service\Api\Search\Repository\OfferRepositoryInterface;
use AppBundle\Service\Api\Legacy\StartingPrice;
use AppBundle\Concern\LocaleConcern;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory as PsrFactory;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class SearchService
{
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SearchBuilder
     */
    private $searchBuilder;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var LocaleConcern
     */
    private $locale;

    /**
     * @var SurveyService
     */
    private $surveyService;

    /**
     * @var StartingPrice
     */
    private $startingPrice;

    /**
     * @var PriceRepositoryInterface
     */
    private $priceRepository;

    /**
     * @var OfferRepositoryInterface
     */
    private $offerRepository;

    /**
     * Constructor
     */
    public function __construct(RepositoryInterface $repository, LocaleConcern $locale, array $config)
    {
        $this->repository = $repository;
        $this->locale     = $locale;
        $this->config     = $config;

        $this->setLimit($this->config['service']['api']['search']['limit']);
    }

    /**
     * @param SurveyService
     */
    public function setSurveyService($surveyService)
    {
        $this->surveyService = $surveyService;
    }

    /**
     * @param StartingPrice $startingPrice
     *
     * @return Repository
     */
    public function setStartingPrice(StartingPrice $startingPrice)
    {
        $this->startingPrice = $startingPrice;
    }

    /**
     * @param PriceRepositoryInterface $priceRepository
     */
    public function setPriceRepository(PriceRepositoryInterface $priceRepository)
    {
        $this->priceRepository = $priceRepository;
    }

    /**
     * @param OfferRepositoryInterface $offerRepository
     */
    public function setOfferRepository(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    /**
     * This can only be called once per request!
     *
     * @param Request $request
     *
     * @return Params
     */
    public function createParamsFromRequest(Request $request)
    {
        $factory = new PsrFactory();
        return new Params($factory->createRequest($request));
    }

    /**
     * @param Params $params
     *
     * @return Resultset
     */
    public function search(Params $params, $weekendski = false)
    {
        $this->filterBuilder = new FilterBuilder($params->getFilters() ?: []);

        $this->searchBuilder = new SearchBuilder();
        $this->addClauses($this->searchBuilder, $params);

        $weekendski = new Where(Where::WHERE_WEEKEND_SKI, (true === $weekendski ? 1 : 0));
        $this->searchBuilder->addClause($weekendski);

        $results = $this->repository->search($this->searchBuilder, $this->filterBuilder);
        $resultset = new Resultset($results, $this->config, $params->getWeekend(), $params->getPersons());
        $resultset->setStartingPrice($this->startingPrice);
        $resultset->setPriceRepository($this->priceRepository);
        $resultset->setOfferRepository($this->offerRepository);
        $resultset->prepare();

        return $resultset->sort(new Sort(Sort::FIELD_TYPE_SEARCH_ORDER, $params->getSort() ?: $this->getDefaultSort()));
    }

    /**
     * @param Resultset $resultset
     * @param Params    $params
     *
     * @return Paginator
     */
    public function paginate(Resultset $resultset, Params $params)
    {
        return $resultset->paginate($params->getPage(), $this->getLimit());
    }

    /**
     * @param Resultset $resultset
     *
     * @return array
     */
    public function surveys(Resultset $resultset)
    {
        $data    = $this->surveyService->statsByTypes($resultset->getTypeIds());
        $surveys = [];

        foreach ($data as $survey) {

            $surveys[intval($survey['typeId'])] = [

                'type_id' => intval($survey['typeId']),
                'count'   => intval($survey['surveyCount']),
                'average' => floatval($survey['surveyAverageOverallRating']),
            ];
        }

        return $surveys;
    }

    /**
     * @param Resultset $resultset
     *
     * @return array
     */
    public function extractTypeIds(Resultset $resultset)
    {
        return $resultset->getTypeIds();
    }

    /**
     * @param Resultset $resultset
     * @param array     $ids
     *
     * @return array
     */
    public function extractNames(Resultset $resultset, array $ids)
    {
        $names   = [];
        $results =& $resultset->results;

        foreach ($results as $result) {

            foreach ($result as $row) {

                if (in_array($row['accommodation_id'], $ids)) {
                    $names[$row['accommodation_id']] = $row['accommodation_name'];
                }
            }
        }

        return $names;
    }

    /**
     * @param Resultset $resultset
     * @param array     $filters
     *
     * @return FacetService
     */
    public function facets(Resultset $resultset, $filters)
    {
        $facetService = new FacetService($resultset, $filters, $this->config);
        $facetService->calculate();

        return $facetService;
    }

    /**
     * @param Params $params
     *
     * @return boolean
     */
    public function hasDestination(Params $params)
    {
        $destination = false;

        if (false !== $params->getCountries()) {
            $destination = true;
        }

        if (false !== $params->getRegions()) {
            $destination = true;
        }

        if (false !== $params->getPlaces()) {
            $destination = true;
        }

        return $destination;
    }

    /**
     * @param integer $limit
     *
     * @return SearchService
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return integer
     */
    public function getDefaultSort()
    {
        return intval($this->config['service']['api']['search']['sort']);
    }

    /**
     * @param Params $params
     *
     * @return SearchBuilder
     */
    private function addClauses(SearchBuilder $builder, Params $params)
    {
        if (false !== ($countries = $params->getCountries())) {
            $builder->addClause(new Where(Where::WHERE_COUNTRY, $countries));
        }

        if (false !== ($regions = $params->getRegions())) {
            $builder->addClause(new Where(Where::WHERE_REGION, $regions));
        }

        if (false !== ($places = $params->getPlaces())) {
            $builder->addClause(new Where(Where::WHERE_PLACE, $places));
        }

        if (false !== ($accommodations = $params->getAccommodations())) {
            $builder->addClause(new Where(Where::WHERE_ACCOMMODATION, array_map('intval', $accommodations)));
        }

        if (false !== ($bedrooms = $params->getBedrooms())) {
            $builder->addClause(new Where(Where::WHERE_BEDROOMS, $bedrooms));
        }

        if (false !== ($bathrooms = $params->getBathrooms())) {
            $builder->addClause(new Where(Where::WHERE_BATHROOMS, $bathrooms));
        }

        if (false !== ($persons = $params->getPersons())) {
            $builder->addClause(new Where(Where::WHERE_PERSONS, $persons));
        }

        if (false !== ($weekend = $params->getWeekend())) {
            $builder->addClause(new Where(Where::WHERE_WEEKEND, $weekend));
        }

        return $builder;
    }
}