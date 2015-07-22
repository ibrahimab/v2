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
     * @var PaginatorService
     */
    private $paginator;

    /**
     * @var integer
     */
    private $order_by;

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
    public function __construct(PaginatorService $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param integer $by
     */
    public function sort($by = self::SORT_ASC)
    {
        $this->setOrderBy($by);
        $this->setSortKeys();

        $accommodations = $this->paginator->results();
        $sortable       = [];

        foreach ($accommodations as $accommodation) {

            $types = $accommodation->getTypes();

            foreach ($types as $type) {
                $sortable[] = $type;
            }
        }

        dump(array_map(function($r) { return $r->getSortKey(); }, $sortable));

        usort($sortable, function($a, $b) {
            return $a->getSortKey() < $b->getSortKey() ? -1 : 1;
        });

        dump(array_map(function($r) { return $r->getSortKey(); }, $sortable));

        exit;
    }

    /**
     * @param integer $by
     */
    public function setOrderBy($by)
    {
        $this->order_by = $by;
    }

    /**
     * @return integer
     */
    public function getOrderBy()
    {
        return $this->order_by;
    }

    /**
     * @return void
     */
    public function setSortKeys()
    {
        $results = $this->paginator->results();
        $locale  = $this->getLocale();

        foreach ($results as $accommodation) {

            $types = $accommodation->getTypes();

            foreach ($types as $type) {

                $key = '';
                if ($type->getPrice() > 0) {
                    $key .= '1';
                } else {
                    $key .= '9';
                }

                if ($this->getOrderBy() === self::SORT_ASC) {
                    $key .= substr('0000000' . number_format($type->getPrice(), 2, '', ''), -7) . '-';
                } else {
                    $key .= 1000000 - $type->getPrice();
                }

                $order = $type->getSupplier()->getSearchOrder();

                if ($accommodation->getSearchOrder() !== 3) {
                    $order = $accommodation->getSearchOrder();
                }

                if ($type->getSearchOrder() !== 3) {
                    $order = $type->getSearchOrder();
                }

                $place = $accommodation->getPlace();
                $key  .= $order . '-';
                $key  .= $place->getRegion()->getLocaleName($locale) . '-';
                $key  .= $place->getLocaleName($locale) . '-';
                $key  .= $accommodation->getLocaleName($locale) . '-';
                $key  .= sprintf('%03d', $type->getMaxResidents()) . '-';
                $key  .= $type->getId();

                $type->setSortKey($key);
            }
        }
    }
}