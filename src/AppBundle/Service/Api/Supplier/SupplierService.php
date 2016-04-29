<?php
namespace AppBundle\Service\Api\Supplier;

/**
 * This is the SupplierService, with this service you can manipulate suppliers
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 */
class SupplierService
{
    /**
     * @var SupplierServiceRepositoryInterface
     */
    private $supplierServiceRepository;

    /**
     * Constructor
     *
     * @param SupplierServiceRepositoryInterface $supplierServiceRepository
     */
    public function __construct(SupplierServiceRepositoryInterface $supplierServiceRepository)
    {
        $this->supplierServiceRepository = $supplierServiceRepository;
    }

    /**
     * Fetch all the suppliers
     *
     * Fetching all the suppliers based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return SupplierServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->supplierServiceRepository->all($options);
    }

    /**
     * Finding a single supplier, based on criteria passed in
     *
     * @param array $by
     * @return SupplierServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->supplierServiceRepository->find($by);
    }
}
