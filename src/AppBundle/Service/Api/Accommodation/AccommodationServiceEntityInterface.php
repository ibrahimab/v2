<?php
namespace AppBundle\Service\Api\Accommodation;

interface AccommodationServiceEntityInterface {
    
    /**
     * @return int
     */
    public function getId();
    
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @param string $name
     * @return AccommodationEntityInterface
     */
    public function setName($name);
}