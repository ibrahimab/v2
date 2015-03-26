<?php
namespace AppBundle\Service\Api\Type;
use       AppBundle\Service\Api\Type\TypeServiceRepositoryInterface;

class TypeService
{    
    /**
     * @var TypeServiceRepositoryInterface
     */
    public $typeRepository;
    
    /**
     * Constructor
     * 
     * @param TypeServiceRepositoryInterface $typeRepository
     */
    public function __construct(TypeServiceRepositoryInterface $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }
    
    /**
     * Get all types
     * The options array accepts a 'where', 'order' and 'limit' key that customizes
     * the query
     *
     * @param  array $options
     * @return TypeServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->typeRepository->all($options);
    }
    
    /**
     * Get a single type
     * The options array accepts a 'where', 'order' and 'limit' key that customizes
     *
     * @param  array $by
     * @return TypeServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->typeRepository->find($by);
    }
    
    /**
     * Counting all the types of all the accommodations for all the regions provided
     *
     * @param RegionServiceEntityInterface[] $regions
     * @return array
     */
    public function countByRegions($regions)
    {
        return $this->typeRepository->countByRegions($regions);
    }
}