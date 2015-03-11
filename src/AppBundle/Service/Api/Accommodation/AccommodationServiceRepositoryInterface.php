<?php
namespace AppBundle\Service\Api\Accommodation;

interface AccommodationServiceRepositoryInterface {
    
    /**
     * This method selects all the accommodations
     *
     * @param  array $options
     * @return AcommodationServiceEntityInterface[]
     */
    public function all($options  = []);
    
    /**
     * Select a single accommodation with a flag (be it any field the accommodation has)
     *
     * @param  array $by
     * @return AcommodationServiceEntityInterface|null
     */
    public function find($by = []);
}