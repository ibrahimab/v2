<?php
namespace AppBundle\Service\Api\PricesAndOffers\Repository;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
interface OfferRepositoryInterface
{
    /**
     * @param array|null $typeIds
     *
     * @return array
     */
    public function getOffers($typeIds = null);
}