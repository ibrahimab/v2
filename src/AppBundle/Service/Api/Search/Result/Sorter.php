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
     * @param array $raw
     *
     * @return array
     */
            // only remove main result from type table
            // if type table has more than 1 result
            // if (count($results[$groupId]) > 1) {
            //     unset($results[$groupId][$mainRow['type_key']]);
            // }
    public function sort($raw)
    {
        $results = [];
        $sortable = [];
        $grouped = [];

        foreach ($raw as $rows) {

            foreach ($rows as $row) {

                $row['type_key'] = $this->generateTypeKey($row);
                $row['accommodation_key'] = $this->generateAccommodationKey($row);

                $sortable[$row['type_key']] = $row['group_id'];
                $grouped[$row['group_id']][$row['accommodation_key']] = $row;
            }
        }

        ksort($sortable);

        foreach ($sortable as $typeKey => $groupId) {

            ksort($grouped[$groupId]);
            $results[$groupId] = $grouped[$groupId];
        }

        return $results;
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