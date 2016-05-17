<?php
namespace AppBundle\Service\Api\Search\Repository;

use AppBundle\Service\Api\Search\Params;

/**
 * @author  Ibrahim Abdullah
 * @package Chalet
 */
interface FilterNamesRepositoryInterface
{
    /**
     * @param Params $params
     *
     * @return array
     */
    public function get(Params $params);
}