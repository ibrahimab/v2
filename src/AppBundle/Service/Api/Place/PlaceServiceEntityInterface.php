<?php
namespace AppBundle\Service\Api\Place;

use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;

interface PlaceServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId();

    /**
     * Set siblingId
     *
     * @param integer $siblingId
     * @return PlaceServiceEntityInterface
     */
    public function setSiblingId($siblingId);

    /**
     * Get siblingId
     *
     * @return integer 
     */
    public function getSiblingId();
    
    /**
     * Set Sibling
     *
     * @param PlaceServiceEntityInterface $sibling
     * @return PlaceServiceEntityInterface
     */
    public function setSibling($sibling);
    
    /**
     * Get Sibling
     *
     * @return PlaceServiceEntityInterface
     */
    public function getSibling();

    /**
     * Set regionId
     *
     * @param integer $regionId
     * @return PlaceServiceEntityInterface
     */
    public function setRegionId($regionId);

    /**
     * Get regionId
     *
     * @return integer 
     */
    public function getRegionId();

    /**
     * Set region
     *
     * @param integer $region
     * @return PlaceServiceEntityInterface
     */
    public function setRegion($region);

    /**
     * Get region
     *
     * @return RegionServiceEntityInterface 
     */
    public function getRegion();
    
    /**
     * Set Accommodations
     *
     * @param AccommodationServiceEntityInterface[] $accommodations
     * @return RegionServiceEntityInterface
     */
    public function setAccommodations($accommodations);
    
    /**
     * Get Accommodations
     *
     * @return AccommodationServiceEntityInterface[]
     */
    public function getAccommodations();
    
    /**
     * Set Country
     * 
     * @param CountryServiceEntityInterface $country
     * @return PlaceServiceEntityInterface
     */
    public function setCountry($country);
    
    /**
     * Get Country
     *
     * @return CountryServiceEntityInterface
     */
    public function getCountry();

    /**
     * Set season
     *
     * @param integer $season
     * @return PlaceServiceEntityInterface
     */
    public function setSeason($season);

    /**
     * Get season
     *
     * @return integer 
     */
    public function getSeason();

    /**
     * Set name
     *
     * @param string $name
     * @return PlaceServiceEntityInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string 
     */
    public function getName();

    /**
     * Set alternativeName
     *
     * @param string $alternativeName
     * @return PlaceServiceEntityInterface
     */
    public function setAlternativeName($alternativeName);

    /**
     * Get alternativeName
     *
     * @return string 
     */
    public function getAlternativeName();

    /**
     * Set visibleAlternativeName
     *
     * @param string $visibleAlternativeName
     * @return PlaceServiceEntityInterface
     */
    public function setVisibleAlternativeName($visibleAlternativeName);

    /**
     * Get visibleAlternativeName
     *
     * @return string 
     */
    public function getVisibleAlternativeName();

    /**
     * Set websites
     *
     * @param array $websites
     * @return PlaceServiceEntityInterface
     */
    public function setWebsites($websites);

    /**
     * Get websites
     *
     * @return array 
     */
    public function getWebsites();

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return PlaceServiceEntityInterface
     */
    public function setShortDescription($shortDescription);

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return PlaceServiceEntityInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription();
    
    /**
     * Set latitude
     *
     * @param string $latitude
     * @return PlaceServiceEntityInterface
     */
    public function setLatitude($latitude);
    
    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude();
    
    /**
     * Set longitude
     *
     * @param string $longitude
     * @return PlaceServiceEntityInterface
     */
    public function setLongitude($longitude);
    
    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude();

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PlaceServiceEntityInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt();

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return PlaceServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt();
}