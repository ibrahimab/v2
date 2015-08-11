<?php
namespace AppBundle\Service\Api\Search\Result;
use       AppBundle\AppTrait\LocaleTrait;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Sorter
{
    use LocaleTrait;

    /**
     * @var Resultset
     */
    private $resultset;

    /**
     * @var integer
     */
    private $persons;

    /**
     * @var integer
     */
    private $order_by = self::SORT_NORMAL;

    /**
     * @param array
     */
    private $optimalMaximumPersonsMap;

    /**
     * @const integer
     */
    const SORT_NORMAL = 1;

    /**
     * @const integer
     */
    const SORT_ASC  = 2;

    /**
     * @const integer
     */
    const SORT_DESC = 3;

    /**
     * @param PaginatorService $paginator
     */
    public function __construct(Resultset $resultset)
    {
        $this->resultset = $resultset;
    }

    /**
     * @param array $maximumPersonsMap
     */
    public function setOptimalMaximumPersonsMap($app)
    {
        $this->optimalMaximumPersonsMap = $app['optimal_maximum_persons_map'];
    }

    /**
     * @return void
     */
    public function sort()
    {
        $sortable         = [];
        $resultGroups     = [];
        $accommodations   = [];
        $priceGroups      = [];
        $priceTypesGroups = [];
        $cheapest         = [];

        foreach ($this->resultset->results as $accommodation) {

            $types = $accommodation['types'];
            unset($accommodation['types']);

            foreach ($types as $type) {

                $groupId                    = ($type['singleInSearch'] ? ($accommodation['id'] . '_' . $type['id']) : $accommodation['id']);
                $accommodations[$groupId]   = $accommodation;
                $sortable[$type['sortKey']] = $groupId;
                $accommodationSortKey       = $this->generateAccommodationSortKey($type);

                if (!isset($resultGroups[$groupId])) {
                    $resultGroups[$groupId] = [];
                }

                $resultGroups[$groupId][$accommodationSortKey] = $type;
            }
        }

        ksort($sortable);

        foreach ($resultGroups as $groupId => $types) {

            foreach ($types as $typeKey => $type) {

                if (!isset($priceGroups[$groupId])) {
                    $priceGroups[$groupId] = [];
                }

                if ($type['price'] > 0) {

                    $priceGroups[$groupId][]      = $type['price'];
                    $priceTypesGroups[$groupId][] = $type['id'];
                }
            }

            $min = 0;

            if (count($priceGroups[$groupId]) > 0) {
                $min = min($priceGroups[$groupId]);
            }

            foreach ($priceGroups[$groupId] as $priceKey => $price) {

                if ($min === $price) {
                    $accommodations[$groupId]['cheapest'] = ['id' => $priceTypesGroups[$groupId][$priceKey], 'price' => ceil($price)];
                }
            }
        }

        $results = [];
        foreach ($sortable as $sortKey => $groupId) {

            ksort($resultGroups[$groupId]);

            $results[$groupId]          = $accommodations[$groupId];
            $results[$groupId]['types'] = array_values($resultGroups[$groupId]); // reset keys
        }

        $this->resultset->setSortedResults(array_values($results)); // reset keys
    }

    /**
     * @param integer $by
     */
    public function setOrderBy($by)
    {
        $this->order_by = $by;

        return $this;
    }

    /**
     * @return integer
     */
    public function getOrderBy()
    {
        return $this->order_by;
    }

    /**
     * @var integer
     */
    public function setPersons($persons)
    {
        $this->persons = $persons;
    }

    /**
     * @return string
     */
    public function generateSortKey($accommodation, $type)
    {
        $key = '';

        $order = $type['supplier']['searchOrder'];

        if ($accommodation['searchOrder'] !== 3) {
            $order = $accommodation['searchOrder'];
        }

        if ($type['searchOrder'] !== 3) {
            $order = $type['searchOrder'];
        }

        switch ($this->getOrderBy()) {

            case self::SORT_ASC:

                $key .= ($type['price'] > 0 ? 1 : 9);
                $key .= substr('0000000' . number_format($type['price'], 2, '', ''), -7) . '-';

            break;

            case self::SORT_DESC:

                $key .= ($type['price'] > 0 ? 1 : 9);
                $key .= 1000000 - $type['price'];
            break;

            case self::SORT_NORMAL:
            default:

                $key .= ($type['price'] > 0 ? 1 : 9);

                if (null !== $this->persons) {

                    if ($this->persons > 20) {

                        $min = intval($this->persons);
                        $max = 50;

                    } else {

                        $min = intval($this->persons);
                        $max = intval(isset($this->optimalMaximumPersonsMap[$this->persons]) ? $this->optimalMaximumPersonsMap[$this->persons] : $this->persons);
                    }

                    if ($type['optimalResidents'] >= $min && $type['maxResidents'] <= $max) {
                        $key .= '22-';
                    } else {
                        $key .= '88-';
                    }
                }

                if (isset($type['accommodation']) && true === $type['accommodation']) {
                    $key .= 'ZZZ';
                } else {
                    $key .= 'AAA';
                }

                $key .= $order . '-';
        }

        $key .= $order . '-';
        $key .= $accommodation['place']['region']['localeName'] . '-' . $accommodation['place']['localeName'] . '-' . $accommodation['localeName'] . '-' . sprintf('%03d', $type['maxResidents']) . '-' . $type['id'];
        $key .= $accommodation['place']['region']['localeName'] . '-' . $accommodation['place']['localeName'] . '-' . $accommodation['localeName'] . '-' . sprintf('%03d', $type['maxResidents']) . '-' . $type['id'];

        return $key;
    }

    public function generateAccommodationSortKey($type)
    {
        $key = '';

        if ($type['price'] > 0) {

            $key   .= '1';
            $price  = $type['price'];

        } else {

            $key   .= '9';
            $price  = '99999999';
        }

        $key .= substr('0000' . $type['optimalResidents'], -4) . '_' . substr('0000' . $type['maxResidents'], -4) . '_' . substr('0000000000' . number_format($price, 2, '', ''), -10) . '_' . $type['id'];
        return $key;
    }
}