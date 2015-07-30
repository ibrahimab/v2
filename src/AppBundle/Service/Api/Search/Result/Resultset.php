<?php
namespace AppBundle\Service\Api\Search\Result;
use       AppBundle\Entity\Accommodation\Accommodation;
use       AppBundle\Service\Api\Search\Result\Paginator\Paginator;
use       AppBundle\AppTrait\LocaleTrait;
use       Doctrine\ORM\QueryBuilder;
use       Doctrine\ORM\Query;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Resultset
{
    use LocaleTrait;

    /**
     * @var integer
     */
    const SORT_ASC  = 1;

    /**
     * @var integer
     */
    const SORT_DESC = 2;

    /**
     * @var QueryBuilder
     */
    private $builder;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var Sorter
     */
    private $sorter;

    /**
     * @var array
     */
    public $results = [];

    /**
     * @var array
     */
    public $types = [];

    /**
     * @var integer
     */
    public $count;

    /**
     * @var integer
     */
    public $total;

    /**
     * @var array
     */
    public $prices;

    /**
     * @var array
     */
    public $offers;

    /**
     * @var array
     */
    public $surveys;

    /**
     * @var array
     */
    public $sortKeys;


    /**
     * @param QueryBuilder $builder
     */
    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
        $this->results = $this->builder->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @return void
     */
    public function prepare()
    {
        foreach ($this->results as $key => $accommodation) {

            $this->results[$key]['cheapest']               = ['id' => $accommodation['types'][0]['id'], 'price' => 0];
            $this->results[$key]['offer']                  = false;
            $this->results[$key]['price']                  = 0;
            $this->results[$key]['localeName']             = $this->getLocaleValue('name', $accommodation);
            $this->results[$key]['localeShortDescription'] = $this->getLocaleValue('shortDescription', $accommodation);
            $this->results[$key]['kindIdentifier']         = (isset(Accommodation::$kindIdentifiers[$accommodation['kind']]) ? Accommodation::$kindIdentifiers[$accommodation['kind']] : null);

            $place   = $accommodation['place'];
            $region  = $place['region'];
            $country = $place['country'];

            $this->results[$key]['place']['localeName']            = $this->getLocaleValue('name', $place);
            $this->results[$key]['place']['country']['localeName'] = $this->getLocaleValue('name', $country);
            $this->results[$key]['place']['region']['localeName']  = $this->getLocaleValue('name', $region);

            foreach ($accommodation['types'] as $typeKey => $type) {

                $this->results[$key]['types'][$typeKey]['price']                      = 0;
                $this->results[$key]['types'][$typeKey]['offer']                      = false;
                $this->results[$key]['types'][$typeKey]['surveyCount']                = 0;
                $this->results[$key]['types'][$typeKey]['surveyAverageOverallRating'] = 0;
                $this->results[$key]['types'][$typeKey]['sortKey']                    = '-';
                $this->results[$key]['types'][$typeKey]['localeName']                 = $this->getLocaleValue('name', $type);
            }

            $this->types[$accommodation['id']] =& $this->results[$key]['types'];
        }
    }

    /**
     * @return Paginator
     */
    public function paginator()
    {
        if (null === $this->paginator) {
            $this->paginator = new Paginator($this);
        }

        return $this->paginator;
    }

    public function sorter()
    {
        if (null === $this->sorter) {

            $this->sorter = new Sorter($this);
            $this->sorter->setLocale($this->getLocale());
        }

        return $this->sorter;
    }

    /**
     * @return integer
     */
    public function count()
    {
        if (null === $this->count) {
            $this->count = count($this->results);
        }

        return $this->count;
    }

    /**
     * @return integer
     */
    public function total()
    {
        if (null === $this->blocks) {

            $this->total = 0;

            foreach ($this->results as $accommodation) {

                if (isset($accommodation['types'])) {
                    $this->total += count($accommodation['types']);
                }
            }
        }

        return $this->total;
    }

    /**
     * @param integer $accommodationId
     * @return array
     */
    public function types($accommodationId)
    {
        return (isset($this->types[$accommodationId]) ? $this->types[$accommodationId] : []);
    }

    /**
     * @return array
     */
    public function allTypes()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function allTypeIds()
    {
        $ids = [];

        foreach ($this->types as $accommodationId => $types) {

            foreach ($types as $type) {
                $ids[] = $type['id'];
            }
        }

        return $ids;
    }

    /**
     * @return array
     */
    public function currentPageTypeIds()
    {
        $paginator = $this->paginator();
        $ids       = [];

        foreach ($paginator as $accommodation) {

            foreach ($accommodation['types'] as $type) {
                $ids[] = $type['id'];
            }
        }

        return $ids;
    }

    /**
     * @param array $prices
     */
    public function setPrices($prices)
    {
        $this->prices = $prices;
    }

    /**
     * @param array $offers
     */
    public function setOffers($offers)
    {
        $this->offers = $offers;
    }

    /**
     * @param array surveys
     */
    public function setSurveys($surveys)
    {
        $this->surveys = $surveys;
    }

    /**
     * @return void
     */
    public function setMetadata()
    {
        foreach ($this->results as $key => $accommodation) {

            $accommodationPrices[$accommodation['id']] = 0;
            $this->results[$key]['prices']             = [];
            $this->results[$key]['prices_types']       = [];

            foreach ($accommodation['types'] as $typeKey => $type) {

                if (isset($this->prices[$type['id']]) && $this->prices[$type['id']] > 0) {

                    $this->results[$key]['types'][$typeKey]['price'] = $this->prices[$type['id']];
                    $this->results[$key]['prices'][]                 = $this->prices[$type['id']];
                    $this->results[$key]['prices_types'][]           = $typeKey;
                }

                if (isset($this->offers[$type['id']])) {
                    $this->results[$key]['types'][$typeKey]['offer'] = true;
                }

                if (isset($this->surveys[$type['id']])) {

                    $this->results[$key]['types'][$typeKey]['surveyCount']                = $this->surveys[$type['id']]['surveyCount'];
                    $this->results[$key]['types'][$typeKey]['surveyAverageOverallRating'] = $this->surveys[$type['id']]['surveyAverageOverallRating'];
                }

                $this->results[$key]['types'][$typeKey]['sortKey'] = $this->sorter()->generateSortKey($this->results[$key], $this->results[$key]['types'][$typeKey]);
            }

            if (count($this->results[$key]['prices']) > 0) {

                $this->results[$key]['price'] = min($this->results[$key]['prices']);

                foreach ($this->results[$key]['prices'] as $priceKey => $price) {

                    if ($price === $this->results[$key]['price']) {
                        $this->results[$key]['cheapest'] =& $this->results[$key]['types'][$this->results[$key]['prices_types'][$priceKey]];
                    }
                }

                unset($this->results[$key]['prices']);
                unset($this->results[$key]['prices_types']);
            }
        }
    }

    /**
     * @param array $sortKey
     */
    public function setSortKeys($sortKeys)
    {
        $this->sortKeys = $sortKeys;
    }

    /**
     * @param array $results
     */
    public function setSortedResults($results)
    {
        $this->results = $results;
    }

    /**
     * @param  string $field
     * @param  array  $data
     * @return string
     */
    public function getLocaleValue($field, $data)
    {
        switch ($this->getLocale()) {

            case 'nl':
                $value = $field;
            break;

            case 'en':
                $value = 'english' . ucfirst($field);
            break;

            case 'de':
                $value = 'german' . ucfirst($field);
            break;

            default:
                $value = '';
        }

        return (isset($data[$value]) ? $data[$value] : '');
    }
}