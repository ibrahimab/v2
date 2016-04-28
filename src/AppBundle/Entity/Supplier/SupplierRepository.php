<?php
namespace AppBundle\Entity\Supplier;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Supplier\SupplierServiceRepositoryInterface;

/**
 * SupplierRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class SupplierRepository extends BaseRepository implements SupplierServiceRepositoryInterface
{
}
