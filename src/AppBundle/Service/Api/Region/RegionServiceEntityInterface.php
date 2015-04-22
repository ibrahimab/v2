<?php
namespace AppBundle\Service\Api\Region;

interface RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return RegionServiceEntityInterface
     */
    public function setShortDescription($shortDescription);

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription();
    
    /**
     * Set Locale short descriptions
     * 
     * @param array $localeShortDescriptions
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription();
    
    /**
     * Set locale descriptions
     *
     * @param array $localeDescriptions
     * @return RegionServiceEntityInterface
     */
    public function setLocaleDescriptions($localeDescriptions);
    
    /**
     * Get locale description
     *
     * @return string
     */
    public function getLocaleDescription($locale);

    /**
     * Set alternativeName
     *
     * @param string $alternativeName
     * @return RegionServiceEntityInterface
     */
    public function setAlternativeName($alternativeName);

    /**
     * Get alternativeName
     *
     * @return string 
     */
    public function getAlternativeName();

    /**
     * Set season
     *
     * @param integer $season
     * @return RegionServiceEntityInterface
     */
    public function setSeason($season);

    /**
     * Get season
     *
     * @return integer 
     */
    public function getSeason();

    /**
     * Set websites
     *
     * @param array $websites
     * @return RegionServiceEntityInterface
     */
    public function setWebsites($websites);

    /**
     * Get websites
     *
     * @return array 
     */
    public function getWebsites();

    /**
     * Set minimumAltitude
     *
     * @param integer $minimumAltitude
     * @return RegionServiceEntityInterface
     */
    public function setMinimumAltitude($minimumAltitude);

    /**
     * Get minimumAltitude
     *
     * @return integer 
     */
    public function getMinimumAltitude();

    /**
     * Set maximumAltitude
     *
     * @param integer $maximumAltitude
     * @return RegionServiceEntityInterface
     */
    public function setMaximumAltitude($maximumAltitude);

    /**
     * Get maximumAltitude
     *
     * @return integer 
     */
    public function getMaximumAltitude();
    
    /**
     * Set total lifts
     *
     * @param integer $totalLifts
     * @return RegionServiceEntityInterface
     */
    public function setTotalLifts($totalLifts);
    
    /**
     * Get total lifts
     *
     * @return integer
     */
    public function getTotalLifts();
    
    /**
     * Set total slopes distance
     *
     * @param integer $totalSlopesDistance
     * @return RegionServiceEntityInterface
     */
    public function setTotalSlopesDistance($totalSlopesDistance);
    
    /**
     * Get total total slopes distance
     *
     * @return integer
     */
    public function getTotalSlopesDistance();
    
    /**
     * Set total blue slopes
     *
     * @param integer $totalBlueSlopes
     * @return RegionServiceEntityInterface
     */
    public function setTotalBlueSlopes($totalBlueSlopes);
    
    /**
     * Get total blue slopes
     *
     * @return integer
     */
    public function getTotalBlueSlopes();
    
    /**
     * Set total red slopes
     *
     * @param integer $totalRedSlopes
     * @return RegionServiceEntityInterface
     */
    public function setTotalRedSlopes($totalRedSlopes);
    
    /**
     * Get total red slopes
     *
     * @return integer
     */
    public function getTotalRedSlopes();
    
    /**
     * Set total black slopes
     *
     * @param integer $totalRedSlopes
     * @return RegionServiceEntityInterface
     */
    public function setTotalBlackSlopes($totalBlackSlopes);
    
    /**
     * Get total black slopes
     *
     * @return integer
     */
    public function getTotalBlackSlopes();
    
    /**
     * Set types count
     *
     * @param integer $typesCount
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RegionServiceEntityInterface
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
     * @return RegionServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt();
}