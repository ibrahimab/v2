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
    private $order_by = self::SORT_ASC;

    /**
     * @const integer
     */
    const SORT_ASC  = 1;

    /**
     * @const integer
     */
    const SORT_DESC = 2;

    /**
     * @param PaginatorService $paginator
     */
    public function __construct(Resultset $resultset)
    {
        $this->resultset = $resultset;
    }

    /**
     * @return void
     */
    public function sort()
    {
        $sorted         = [];
        $sortable       = [];
        $accommodations = [];

        foreach ($this->resultset->results as $accommodation) {

            $accommodations[$accommodation['id']] = $accommodation;

            foreach ($accommodation['types'] as $type) {

                $accommodationId            = $type['accommodationId'];
                $sortable[$type['sortKey']] = $accommodationId;
                $accommodationSortKey       = $this->generateAccommodationSortKey($type);

                if (!isset($sorted[$accommodationId])) {
                    $sorted[$accommodationId] = [];
                }

                $sorted[$accommodationId][$accommodationSortKey] = $type;
            }
        }

        ksort($sortable);

        $results = [];
        foreach ($sortable as $sortKey => $accommodationId) {

            ksort($sorted[$accommodationId]);

            $results[$accommodationId]          = $accommodations[$accommodationId];
            $results[$accommodationId]['types'] = array_values($sorted[$accommodationId]); // reset keys
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
     * @return string
     */
    public function generateSortKey($accommodation, $type)
    {
        $key = '';

        if ($type['price'] > 0) {
            $key .= '1';
        } else {
            $key .= '9';
        }

        if ($this->getOrderBy() === self::SORT_ASC) {
            $key .= substr('0000000' . number_format($type['price'], 2, '', ''), -7) . '-';
        } else {
            $key .= 1000000 - $type['price'];
        }

        $order = $type['supplier']['searchOrder'];

        if ($accommodation['searchOrder'] !== 3) {
            $order = $accommodation['searchOrder'];
        }

        if ($type['searchOrder'] !== 3) {
            $order = $type['searchOrder'];
        }

        $key  .= $order . '-';
        $key  .= $accommodation['place']['region']['localeName'] . '-';
        $key  .= $accommodation['place']['localeName'] . '-';
        $key  .= $accommodation['localeName'] . '-';
        $key  .= sprintf('%03d', $type['maxResidents']) . '-';
        $key  .= $type['id'];

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