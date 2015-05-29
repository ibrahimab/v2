<?php
namespace AppBundle\Service\Api\Search;

/**
 * SearchService repository interface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
interface SearchServiceRepositoryInterface
{
    public function search(SearchBuilder $searchBuilder);
}