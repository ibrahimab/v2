<?php
namespace AppBundle\Service\Api\Accommodation;
use       AppBundle\Entity\Accommodation\Accommodation;

interface AccommodationServiceRepositoryInterface {
    
    /**
     * This method selects all the accommodations
     *
     * @param  array $options
     * @return Accommodation[]|[]
     */
    public function all($options  = []);
    
    /**
     * Select a single accommodation with a flag (be it any field the accommodation has)
     *
     * @param  array $by
     * @return Accommodation|null
     */
    public function find($by);
}