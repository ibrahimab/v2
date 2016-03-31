<?php
namespace AppBundle\Service\Api\Search\Repository;

use AppBundle\Service\Api\Search\Builder\Builder as SearchBuilder;
use AppBundle\Service\Api\Search\Filter\Builder  as FilterBuilder;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
interface RepositoryInterface
{
    /**
     * @param FilterBuilder $filterBuilder
     * @param SearchBuilder $searchBuilder
     *
     * @return array
     */
    public function search(SearchBuilder $searchBuilder, FilterBuilder $filterBuilder);
}