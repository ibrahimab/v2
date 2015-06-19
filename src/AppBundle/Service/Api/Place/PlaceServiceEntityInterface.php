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
     * Set english name
     *
     * @param string $englishName
     * @return PlaceServiceEntityInterface
     */
    public function setEnglishName($englishName);
    
    /**
     * Get english name
     *
     * @return string
     */
    public function getEnglishName();
    
    /**
     * Set German name
     *
     * @param string $germanName
     * @return PlaceServiceEntityInterface
     */
    public function setGermanName($germanName);
    
    /**
     * Get German name
     *
     * @return string
     */
    public function getGermanName();
    
    /**
     * Set locale names
     * 
     * @param array $localeNames
     * @return PlaceServiceEntityInterface
     */
    public function setLocaleNames($localeNames);
    
    /**
     * Get locale name
     *
     * @param string $locale
     * @return string
     */
    public function getLocaleName($locale);

    /**
     * Set seo name
     *
     * @param string $seoName
     * @return PlaceServiceEntityInterface
     */
    public function setSeoName($seoName);

    /**
     * Get seo name
     *
     * @return string 
     */
    public function getSeoName();
    
    /**
     * Set english seo name
     *
     * @param string $englishSeoName
     * @return PlaceServiceEntityInterface
     */
    public function setEnglishSeoName($englishSeoName);
    
    /**
     * Get english seo name
     *
     * @return string
     */
    public function getEnglishSeoName();
    
    /**
     * Set German seo name
     *
     * @param string $germanSeoName
     * @return PlaceServiceEntityInterface
     */
    public function setGermanSeoName($germanSeoName);
    
    /**
     * Get German seo name
     *
     * @return string
     */
    public function getGermanSeoName();
    
    /**
     * Set locale seo names
     * 
     * @param array $localeSeoNames
     * @return PlaceServiceEntityInterface
     */
    public function setLocaleSeoNames($localeSeoNames);
    
    /**
     * Get locale seo name
     *
     * @param string $locale
     * @return string
     */
    public function getLocaleSeoName($locale);

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
     * Set englishShortDescription
     *
     * @param string $englishShortDescription
     * @return PlaceServiceEntityInterface
     */
    public function setEnglishShortDescription($englishShortDescription);

    /**
     * Get englishShortDescription
     *
     * @return string 
     */
    public function getEnglishShortDescription();

    /**
     * Set germanShortDescription
     *
     * @param string $germanShortDescription
     * @return PlaceServiceEntityInterface
     */
    public function setGermanShortDescription($germanShortDescription);

    /**
     * Get germanShortDescription
     *
     * @return string 
     */
    public function getGermanShortDescription();
    
    /**
     * Set Locale short descriptions
     * 
     * @param array $localeShortDescriptions
     * @return PlaceServiceEntityInterface
     */
    public function setLocaleShortDescriptions($localeShortDescriptions);

    /**
     * Get Locale short description
     *
     * @param string $locale
     * @return string
     */
    public function getLocaleShortDescription($locale);
    
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
     * Set englishDescription
     *
     * @param string $englishDescription
     * @return PlaceServiceEntityInterface
     */
    public function setEnglishDescription($englishDescription);

    /**
     * Get englishDescription
     *
     * @return string 
     */
    public function getEnglishDescription();

    /**
     * Set germanDescription
     *
     * @param string $germanDescription
     * @return PlaceServiceEntityInterface
     */
    public function setGermanDescription($germanDescription);

    /**
     * Get germanDescription
     *
     * @return string 
     */
    public function getGermanDescription();
    
    /**
     * Set locale descriptions
     *
     * @param array $localeDescriptions
     * @return PlaceServiceEntityInterface
     */
    public function setLocaleDescriptions($localeDescriptions);
    
    /**
     * Get locale description
     *
     * @return string
     */
    public function getLocaleDescription($locale);

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
     * Set altitude
     *
     * @param integer $altitude
     * @return PlaceServiceEntityInterface
     */
    public function setAltitude($altitude);

    /**
     * Get altitude
     *
     * @return integer 
     */
    public function getAltitude();

    /**
     * Set distance from utrecht
     *
     * @param integer $distanceFromUtrecht
     * @return PlaceServiceEntityInterface
     */
    public function setDistanceFromUtrecht($distanceFromUtrecht);

    /**
     * Get distance from utrecht
     *
     * @return integer 
     */
    public function getDistanceFromUtrecht();

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
     * Set types count
     *
     * @param integer $typesCount
     * @return PlaceServiceEntityInterface
     */
    public function setTypesCount($typesCount);
    
    /**
     * Get types count
     *
     * @return integer
     */
    public function getTypesCount();
    
    /**
     * Set average ratings for this region
     *
     * @return PlaceServiceEntityInterface
     */
    public function setAverageRatings($averageRatings);
    
    /**
     * Get average ratings for this region
     * 
     * @return integer
     */
    public function getAverageRatings();
    
    /**
     * Set Ratings count
     *
     * @param integer $ratingsCount
     * @return PlaceServiceEntityInterface
     */
    public function setRatingsCount($ratingsCount);
    
    /**
     * Get Ratings count
     *
     * @return integer
     */
    public function getRatingsCount();
    
    /**
     * Set flag show on homepage
     *
     * @param boolean $showOnHomepage
     * @return PlaceServiceEntityInterface
     */
    public function setShowOnHomepage($showOnHomepage);
    
    /**
     * Get flag show on homepage
     *
     * @return boolean
     */
    public function getShowOnHomepage();

    /**
     * Set features
     *
     * @param array $features
     * @return PlaceServiceEntityInterface
     */
    public function setFeatures($features);

    /**
     * Get features
     *
     * @return array
     */
    public function getFeatures();

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