<?php
namespace AppBundle\Service\Api\Accommodation;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;


interface AccommodationServiceEntityInterface
{
    /**
     * @const integer
     */
    const KIND_CHALET               = 1;
    
    /**
     * @const integer
     */
    const KIND_APARTMENT            = 2;
    
    /**
     * @const integer
     */
    const KIND_HOTEL                = 3;
    
    /**
     * @const integer
     */
    const KIND_CHALET_APARTMENT     = 4;
    
    /**
     * @const integer
     */
    const KIND_HOLIDAY_HOUSE        = 6;
    
    /**
     * @const integer
     */
    const KIND_VILLA                = 7;
    
    /**
     * @const integer
     */
    const KIND_CASTLE               = 8;
    
    /**
     * @const integer
     */
    const KIND_HOLIDAY_PARK         = 9;
    
    /**
     * @const integer
     */
    const KIND_AGRITURISMO          = 10;
    
    /**
     * @const integer
     */
    const KIND_DOMAIN               = 11;
    
    /**
     * @const integer
     */
    const KIND_PENSION              = 12;
    
    /**
     * @return int
     */
    public function getId();
    
    /**
     * @param string $name
     * @return AccommodationServiceEntityInterface
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
    
    /**
     * Setting kind of accommodation
     * 
     * @param integer
     * @return AccommodationServiceEntityInterface
     */
    public function setKind($kind);
    
    /**
     * @return integer
     */
    public function getKind();
    
    /**
     * @return string
     */
    public function getKindIdentifier();

    /**
     * Set display flag
     *
     * @param boolean
     * @return AccommodationServiceEntityInterface
     */
    public function setDisplay($display);

    /**
     * Get display flag
     *
     * @return boolean
     */
    public function getDisplay();

    /**
     * Set weekend ski flag
     *
     * @param boolean
     * @return AccommodationServiceEntityInterface
     */
    public function setWeekendSki($weekendSki);

    /**
     * Get weekend ski flag
     *
     * @return boolean
     */
    public function getWeekendSki();
}