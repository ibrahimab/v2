<?php
namespace AppBundle\Service\Api\Supplier;

/**
 * SupplierServiceRepositoryInterface
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 */
interface SupplierServiceRepositoryInterface
{

    /**
     * Fetching suppliers
     *
     * Fetching all the suppliers based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return SupplierServiceEntityInterface[]
     */
    public function all($options = []);

    /**
     * Finding a single supplier
     *
     * @param array $by
     * @return SupplierServiceEntityInterface
     */
    public function find($by = []);

}
