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
}