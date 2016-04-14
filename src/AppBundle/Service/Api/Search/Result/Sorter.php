<?php
namespace AppBundle\Service\Api\Search\Result;

use AppBundle\Service\Api\Search\Builder\Sort;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Sorter
{
    /**
     * @var integer
     */
    private $direction = Sort::SORT_NORMAL;

    /**
     * @var integer
     */
    private $persons;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array        $config
     * @param integer      $direction
     * @param integer|null $persons
     */
    public function __construct(array $config, $direction = Sort::SORT_NORMAL, $persons = false)
    {
        $this->config = $config;
        $this->setDirection($direction);

        if (false !== $persons) {
            $this->setPersons($persons);
        }
    }

    /**
     * @var integer $direction
     *
     * @return Sorter
     */
    public function setDirection($direction)
    {
        if (!in_array($direction, [Sort::SORT_ASC, Sort::SORT_DESC, Sort::SORT_NORMAL])) {
            throw new SearchException('Sort direction provided is not allowed');
        }

        $this->direction = $direction;
        return $this;
    }

    /**
     * @param integer $persons
     *
     * @return Sorter
     */
    public function setPersons($persons)
    {
        $this->persons = intval($persons);
        return $this;
    }

    /**
     * @param Resultset $resultset
     *
     * @return array
     */
    public function sort(Resultset $resultset)
    {
        $prices   = [];
        $cheapest = [];
        $sortable = [];
        $grouped  = [];

        foreach ($resultset->results as $row) {

            $row['type_key'] = $this->generateTypeKey($row);
            $row['accommodation_key'] = $this->generateAccommodationKey($row);

            if ($row['price'] > 0) {
                $prices[$row['group_id']][$row['type_id']] = $row['price'];
            }

            $sortable[$row['type_key']] = $row['group_id'];
            $grouped[$row['group_id']][$row['accommodation_key']] = $row;
        }

        ksort($sortable);

        // clear old results to reduce memory
        $resultset->results = [];

        // lookup table, type_id to accommodation_key
        $lookup = [];

        foreach ($sortable as $typeKey => $groupId) {

            ksort($grouped[$groupId]);

            // assigning results
            $resultset->results[$groupId] = $grouped[$groupId];

            // total types count is always without the big one,
            // except if the big one is the only one
            $total = count($resultset->results[$groupId]);
            $total = ($total === 1 ? $total : ($total - 1));

            foreach ($resultset->results[$groupId] as &$row) {

                $row['total_types'] = $total;

                if (!isset($resultset->cheapest[$groupId])) {

                    // defaulting cheapest to first row
                    $resultset->cheapest[$groupId] = &$row;
                }

                $lookup[$row['type_id']] = &$row;
            }
        }

        foreach ($prices as $groupId => $groupPrices) {

            $min = min($groupPrices);

            foreach ($groupPrices as $typeId => $price) {

                if ($min === $price && $price > 0) {
                    $resultset->cheapest[$groupId] = &$lookup[$typeId];
                }
            }
        }
    }

    /**
     * @param array
     *
     * @return string
     */
    public function generateTypeKey($row)
    {
        $key = '';

        $row['accommodation_search_order'] = intval($row['accommodation_search_order']);
        $row['type_search_order']          = intval($row['type_search_order']);
        $row['supplier_search_order']      = intval($row['supplier_search_order']);

        $order = $row['supplier_search_order'];

        if ($row['accommodation_search_order'] !== 3) {
            $order = $row['accommodation_search_order'];
        }

        if ($row['type_search_order'] !== 3) {
            $order = $row['type_search_order'];
        }

        switch ($this->direction) {

            case Sort::SORT_ASC:

                $key .= ($row['price'] > 0 ? 1 : 9);
                $key .= substr('0000000' . number_format($row['price'], 2, '', ''), -7) . '-';

                break;

            case Sort::SORT_DESC:

                $key .= ($row['price'] > 0 ? 1 : 9);
                $key .= 1000000 - $row['price'];

                break;

            case Sort::SORT_NORMAL:
            default:

                $key .= ($row['price'] > 0 ? 1 : 9);

                if (null !== $this->persons) {

                    $min = $this->persons;

                    if ($this->persons > 20) {
                        $max = 50;
                    } else {
                        $max = $this->mapMaximumPersons($this->persons);
                    }

                    if ($row['optimal_residents'] >= $min && $row['max_residents'] <= $max) {
                        $key .= '22-';
                    } else {
                        $key .= '88-';
                    }
                }

                if ($row['type'] === 'accommodation') {
                    $key .= 'ZZZ';
                } else {
                    $key .= 'AAA';
                }

                $key .= $order . '-';

                break;
        }

        $key .= $order . '-';

        $key .= $row['region_name'] . '-' . $row['place_name'] . '-' . $row['accommodation_name'] . '-';
        $key .= sprintf('%03d', $row['max_residents']) . '-' . $row['type_id'];

        $key .= $row['region_name'] . '-' . $row['place_name'] . '-' . $row['accommodation_name'] . '-';
        $key .= sprintf('%03d', $row['max_residents']) . '-' . $row['type_id'];

        return $key;
    }

    /**
     * @param array $row
     *
     * @return string
     */
    public function generateAccommodationKey($row)
    {
        $key = '';

        if ($row['price'] > 0) {

            $key  .= '1';
            $price = (string)$row['price'];

        } else {

            $key  .= '9';
            $price = '99999999';
        }

        $key .= substr('0000' . $row['optimal_residents'], -4) . '_' .
                substr('0000' . $row['max_residents'], -4) . '_' .
                substr('0000000000' . number_format($price, 2, '', ''), -10) . '_' .
                $row['type_id'];

        return $key;
    }

    /**
     * @param integer $persons
     *
     * @return integer
     */
    private function mapMaximumPersons($persons)
    {
        return intval((isset($this->config['maximum_persons_map'][$persons]) ? $this->config['maximum_persons_map'][$persons] : $persons));
    }
}