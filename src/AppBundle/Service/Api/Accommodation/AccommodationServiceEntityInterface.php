<?php
namespace AppBundle\Service\Api\Accommodation;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;


interface AccommodationServiceEntityInterface {
    
    /**
     * @return int
     */
    public function getId();
    
    /**
     * @param string $name
     * @return AccommodationEntityInterface
     */
    public function setName($name);
    
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @param  TypeServiceEntityInterface[] $types
     * @return AccommodationServiceEntityInterface
     */
    public function setTypes($types);
    
    /**
     * @return TypeServiceEntityInterface[]
     */
    public function getTypes();
    
    /**
     * Setting Place
     *
     * @param PlaceServiceEntityInterface
     * @return AccommodationServiceEntityInterface
     */
    public function setPlace($place);
    
    /**
     * @return PlaceServiceEntityInterface
     */
    public function getPlace();
}