<?php
namespace AppBundle\Service\Api\Search\Result;
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
     * @var array
     */
    public $results;

    /**
     * @var array
     */
    public $types;

    /**
     * @var integer
     */
    public $count;

    /**
     * @var integer
     */
    public $total;


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

            $this->results[$key]['cheapest']               = ['id' => 0];
            $this->results[$key]['offer']                  = false;
            $this->results[$key]['price']                  = 0;
            $this->results[$key]['localeName']             = $this->getLocaleValue('name', $accommodation);
            $this->results[$key]['localeShortDescription'] = $this->getLocaleValue('shortDescription', $accommodation);

            $place   = $accommodation['place'];
            $region  = $place['region'];
            $country = $place['country'];

            $this->results[$key]['place']['localeName']            = $this->getLocaleValue('name', $place);
            $this->results[$key]['place']['country']['localeName'] = $this->getLocaleValue('name', $country);
            $this->results[$key]['place']['region']['localeName']  = $this->getLocaleValue('name', $region);

            foreach ($accommodation['types'] as $typeKey => $type) {

                $this->results[$key]['types'][$typeKey]['price']       = 0;
                $this->results[$key]['types'][$typeKey]['offer']       = false;
                $this->results[$key]['types'][$typeKey]['surveyCount'] = 0;
                $this->results[$key]['types'][$typeKey]['localeName']  = $this->getLocaleValue('name', $type);
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