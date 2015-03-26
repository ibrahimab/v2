<?php
namespace AppBundle\Service\Api\Type;

interface TypeServiceRepositoryInterface {
    
    /**
     * This method selects all the types based on certain options
     *
     * @param  array $options
     * @return TypeServiceEntityInterface[]
     */
    public function all($options  = []);
    
    /**
     * Select a single type with a flag (be it any field the type has)
     *
     * @param  array $by
     * @return TypeServiceEntityInterface|null
     */
    public function find($by = []);
    
    /**
     * Counting types of all the accommodations for every region given, returns array with region ID as its key and the count as its value
     *
     * @param RegionServiceEntityInterface[] $regions
     * @return array
     */
    public function countByRegions($regions);
}