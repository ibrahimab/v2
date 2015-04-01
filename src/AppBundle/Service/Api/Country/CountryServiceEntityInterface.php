<?php
namespace AppBundle\Service\Api\Country;

interface CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
     */
    public function setGermanName($germanName);
    
    /**
     * Get German name
     *
     * @return string
     */
    public function getGermanName();

    /**
     * Set alternativeName
     *
     * @param string $alternativeName
     * @return CountryServiceEntityInterface
     */
    public function setAlternativeName($alternativeName);

    /**
     * Get alternativeName
     *
     * @return string 
     */
    public function getAlternativeName();
    
    /**
     * Set locale names
     * 
     * @param array $localeNames
     * @return CountryServiceEntityInterface
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
     * Set display
     *
     * @param boolean $display
     * @return CountryServiceEntityInterface
     */
    public function setDisplay($display);

    /**
     * Get display
     *
     * @return boolean 
     */
    public function getDisplay();

    /**
     * Set title
     *
     * @param string $title
     * @return CountryServiceEntityInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle();
    
    /**
     * Set locale titles
     *
     * @param array $localeTitles
     * @return CountryServiceEntityInterface
     */
    public function setLocaleTitles($localeTitles);
    
    /**
     * Get locale title
     *
     * @param string $locale
     * @return string
     */
    public function getLocaleTitle($locale);

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
     */
    public function setLocaleDescriptions($localeDescriptions);
    
    /**
     * Get locale description
     *
     * @return string
     */
    public function getLocaleDescription($locale);

    /**
     * Set colourCode
     *
     * @param integer $colourCode
     * @return CountryServiceEntityInterface
     */
    public function setColourCode($colourCode);

    /**
     * Get colourCode
     *
     * @return integer 
     */
    public function getColourCode();
    
    /**
     * Set Startcode
     *
     * @param string $startCode
     * @return CountryServiceEntityInterface
     */
    public function setStartCode($startCode);
    
    /**
     * Get Start Code
     *
     * @return string
     */
    public function getStartCode();

    /**
     * Set accommodationCodes
     *
     * @param array $accommodationCodes
     * @return CountryServiceEntityInterface
     */
    public function setAccommodationCodes($accommodationCodes);

    /**
     * Get accommodationCodes
     *
     * @return array 
     */
    public function getAccommodationCodes();
    
    /**
     * Set types count
     *
     * @param integer $typesCount
     * @return CountryServiceEntityInterface
     */
    public function setTypesCount($typesCount);
    
    /**
     * Get types count
     *
     * @return integer
     */
    public function getTypesCount();
    
    /**
     * Set average ratings for this country
     *
     * @return CountryServiceEntityInterface
     */
    public function setAverageRatings($averageRatings);
    
    /**
     * Get average ratings for this country
     * 
     * @return integer
     */
    public function getAverageRatings();
    
    /**
     * Set Ratings count
     *
     * @param integer $ratingsCount
     * @return CountryServiceEntityInterface
     */
    public function setRatingsCount($ratingsCount);
    
    /**
     * Get Ratings count
     *
     * @return integer
     */
    public function getRatingsCount();
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return CountryServiceEntityInterface
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
     * @return CountryServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt();
}