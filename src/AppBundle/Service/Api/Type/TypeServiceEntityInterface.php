<?php
namespace AppBundle\Service\Api\Type;
use       AppBundle\Entity\Video;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface TypeServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set price
     *
     * @param float
     * @return TypeServiceEntityInterface
     */
    public function setPrice($price);

    /**
     * Get price
     *
     * @return TypeServiceEntityInterface
     */
    public function getPrice();

    /**
     * Set accommodationId
     *
     * @param  integer $accommodationId
     * @return TypeServiceEntityInterface
     */
    public function setAccommodationId($accommodationId);

    /**
     * Get accommodationId
     *
     * @return integer
     */
    public function getAccommodationId();

    /**
     * Set Accommodation
     *
     * @param  AccommodationServiceEntityInterface $accommodation
     * @return TypeServiceEntityInterface
     */
    public function setAccommodation($accommodation);

    /**
     * Get Accommodation
     *
     * @return AccommodationServiceEntityInterface
     */
    public function getAccommodation();

    /**
     * Set has video flag
     *
     * @param  boolean $hasVideo
     * @return TypeServiceEntityInterface
     */
    public function setHasVideo($hasVideo);

    /**
     * Check if it has a video
     *
     * @return boolean
     */
    public function hasVideo();

    /**
     * Set Video url
     *
     * @param  string $videoUrl
     * @return TypeServiceEntityInterface
     */
    public function setVideoUrl($videoUrl);

    /**
     * Get Video url
     *
     * @return string
     */
    public function getVideoUrl();

    /**
     * Get Video
     *
     * @return Video
     */
    public function getVideo();

    /**
     * Set name
     *
     * @param  string $name
     * @return TypeServiceEntityInterface
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
     * @param  string $englishName
     * @return TypeServiceEntityInterface
     */
    public function setEnglishName($englishName);

    /**
     * Get english name
     *
     * @return string
     */
    public function getEnglishName();

    /**
     * Set german name
     *
     * @param  string $germanName
     * @return TypeServiceEntityInterface
     */
    public function setGermanName($germanName);

    /**
     * Get german name
     *
     * @return string
     */
    public function getGermanName();

    /**
     * Get locale name
     *
     * @param array $localeNames
     * @return TypeServiceEntityInterface
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
     * Set shortDescription
     *
     * @param  string $shortDescription
     * @return TypeServiceEntityInterface
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
     * @param  string $englishShortDescription
     * @return TypeServiceEntityInterface
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
     * @param  string $germanShortDescription
     * @return TypeServiceEntityInterface
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
     * @return TypeServiceEntityInterface
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
     * @return TypeServiceEntityInterface
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
     * @return TypeServiceEntityInterface
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
     * @return TypeServiceEntityInterface
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
     * @return TypeServiceEntityInterface
     */
    public function setLocaleDescriptions($localeDescriptions);

    /**
     * Get locale description
     *
     * @return string
     */
    public function getLocaleDescription($locale);

    /**
     * set layout
     *
     * @param string $layout
     * @return TypeServiceEntityInterface
     */
    public function setLayout($layout);

    /**
     * Get layout
     *
     * @return string
     */
    public function getLayout();

    /**
     * set englishLayout
     *
     * @param string $englishLayout
     * @return TypeServiceEntityInterface
     */
    public function setEnglishLayout($englishLayout);

    /**
     * Get layout
     *
     * @return string
     */
    public function getEnglishLayout();

    /**
     * set germanLayout
     *
     * @param string $germanLayout
     * @return TypeServiceEntityInterface
     */
    public function setGermanLayout($germanLayout);

    /**
     * Get layout
     *
     * @return string
     */
    public function getGermanLayout();

    /**
     * set germanLayout
     *
     * @param string $germanLayout
     * @return TypeServiceEntityInterface
     */
    public function setLocaleLayouts($germanLayout);

    /**
     * Get layout
     *
     * @return string
     */
    public function getLocaleLayout($locale);

    /**
     * Set inventory
     *
     * @param  integer $inventory
     * @return TypeServiceEntityInterface
     */
    public function setInventory($inventory);

    /**
     * Get inventory
     *
     * @return integer
     */
    public function getInventory();

    /**
     * Set websites
     *
     * @param  array $websites
     * @return TypeServiceEntityInterface
     */
    public function setWebsites($websites);

    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites();

    /**
     * Set code
     *
     * @param  string $code
     * @return TypeServiceEntityInterface
     */
    public function setCode($code);

    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

    /**
     * Set display
     *
     * @param  boolean $display
     * @return TypeServiceEntityInterface
     */
    public function setDisplay($display);

    /**
     * Get display
     *
     * @return boolean
     */
    public function getDisplay();

    /**
     * Set display search
     *
     * @param  boolean $displaySearch
     * @return TypeServiceEntityInterface
     */
    public function setDisplaySearch($displaySearch);

    /**
     * Get display search
     *
     * @return boolean
     */
    public function getDisplaySearch();

    /**
     * Set features
     *
     * @param array $features
     * @return TypeServiceEntityInterface
     */
    public function setFeatures($features);

    /**
     * Get features
     *
     * @return array
     */
    public function getFeatures();

    /**
     * Set latitude
     *
     * @param  string $latitude
     * @return TypeServiceEntityInterface
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
     * @param  string $longitude
     * @return TypeServiceEntityInterface
     */
    public function setLongitude($longitude);

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude();

    /**
     * Setting optimal residents
     *
     * @param integer $$optimalResidents
     * @return TypeServiceEntityInterface
     */
    public function setOptimalResidents($optimalResidents);

    /**
     * @return integer
     */
    public function getOptimalResidents();

    /**
     * Setting max residents
     *
     * @param integer $maxResidents
     * @return TypeServiceEntityInterface
     */
    public function setMaxResidents($maxResidents);

    /**
     * @return integer
     */
    public function getMaxResidents();

    /**
     * Set bedrooms
     *
     * @param integer $bedrooms
     * @return TypeServiceEntityInterface
     */
    public function setBedrooms($bedrooms);

    /**
     * Get bedrooms
     *
     * @return integer
     */
    public function getBedrooms();

    /**
     * Set bedroomsextra
     *
     * @param string $bedroomsExtra
     * @return TypeServiceEntityInterface
     */
    public function setBedroomsExtra($bedroomsExtra);

    /**
     * Get bedroomsextra
     *
     * @return string
     */
    public function getBedroomsExtra();

    /**
     * Set english bedroomsextra
     *
     * @param string $englishBedroomsExtra
     * @return TypeServiceEntityInterface
     */
    public function setEnglishBedroomsExtra($englishBedroomsExtra);

    /**
     * Get english bedroomsextra
     *
     * @return string
     */
    public function getEnglishBedroomsExtra();

    /**
     * Set german bedroomsextra
     *
     * @param string $germanBedroomsExtra
     * @return TypeServiceEntityInterface
     */
    public function setGermanBedroomsExtra($germanBedroomsExtra);

    /**
     * Get german bedroomsextra
     *
     * @return string
     */
    public function getGermanBedroomsExtra();

    /**
     * set all locale bedroomsextra fields
     *
     * @param string $localeBedroomsExtras
     * @return TypeServiceEntityInterface
     */
    public function setLocaleBedroomsExtras($localeBedroomsExtras);

    /**
     * Get locale bedroomsextra
     *
     * @return string
     */
    public function getLocaleBedroomsExtra($locale);

    /**
     * Set bathrooms
     *
     * @param integer $bathrooms
     * @return TypeServiceEntityInterface
     */
    public function setBathrooms($bathrooms);

    /**
     * Get bedrooms
     *
     * @return integer
     */
    public function getBathrooms();

    /**
     * Set bathroomsextra
     *
     * @param string $bathroomsExtra
     * @return TypeServiceEntityInterface
     */
    public function setBathroomsExtra($bathroomsExtra);

    /**
     * Get bathroomsextra
     *
     * @return string
     */
    public function getBathroomsExtra();

    /**
     * Set english bathroomsextra
     *
     * @param string $englishBathroomsExtra
     * @return TypeServiceEntityInterface
     */
    public function setEnglishBathroomsExtra($englishBathroomsExtra);

    /**
     * Get english bedroomsextra
     *
     * @return string
     */
    public function getEnglishBathroomsExtra();

    /**
     * Set german bathroomsextra
     *
     * @param string $germanBathroomsExtra
     * @return TypeServiceEntityInterface
     */
    public function setGermanBathroomsExtra($germanBathroomsExtra);

    /**
     * Get german bathroomsextra
     *
     * @return string
     */
    public function getGermanBathroomsExtra();

    /**
     * set all locale bathroomsextra fields
     *
     * @param string $localeBathroomsExtras
     * @return TypeServiceEntityInterface
     */
    public function setLocaleBathroomsExtras($localeBathroomsExtras);

    /**
     * Get locale bathroomsextra
     *
     * @return string
     */
    public function getLocaleBathroomsExtra($locale);

    /**
     * Set surface
     */
    public function setSurface($surface);

    /**
     * Get surface
     */
    public function getSurface();

    /**
     * Set surfaceExtra
     *
     * @param string $surfaceExtra
     * @return TypeServiceEntityInterface
     */
    public function setSurfaceExtra($surfaceExtra);

    /**
     * Get surfaceExtra
     *
     * @return string
     */
    public function getSurfaceExtra();

    /**
     * Set english surfaceExtra
     *
     * @param string $englishSurfaceExtra
     * @return TypeServiceEntityInterface
     */
    public function setEnglishSurfaceExtra($englishSurfaceExtra);

    /**
     * Get english surfaceextra
     *
     * @return string
     */
    public function getEnglishSurfaceExtra();

    /**
     * Set german surfaceextra
     *
     * @param string $germanSurfaceExtra
     * @return TypeServiceEntityInterface
     */
    public function setGermanSurfaceExtra($germanSurfaceExtra);

    /**
     * Get german surfaceextra
     *
     * @return string
     */
    public function getGermanSurfaceExtra();

    /**
     * set all locale surfaceextra fields
     *
     * @param string $localeSurfaceExtras
     * @return TypeServiceEntityInterface
     */
    public function setLocaleSurfaceExtras($localeSurfaceExtras);

    /**
     * Get locale surfaceextra
     *
     * @return string
     */
    public function getLocaleSurfaceExtra($locale);

    /**
     * Setting quality
     *
     * @param integer $quality
     * @return TypeServiceEntityInterface
     */
    public function setQuality($quality);

    /**
     * Getting quality
     *
     * @return integer
     */
    public function getQuality();

    /**
     * Set search order
     *
     * @param  integer $searchOrder
     * @return TypeServiceEntityInterface
     */
    public function setSearchOrder($searchOrder);

    /**
     * Get searchOrder
     *
     * @return integer
     */
    public function getSearchOrder();

    /**
     * @param SurveyServiceEntityInterface[]
     * @return TypeServiceEntityInterface
     */
    public function setSurveys($surveys);

    /**
     * @return SurveyServiceEntityInterface[]
     */
    public function getSurveys();

    /**
     * Set Survey count
     *
     * @param integer $surveyCount
     * @return TypeServiceEntityInterface
     */
    public function setSurveyCount($surveyCount);

    /**
     * Get Survey count
     *
     * @return integer
     */
    public function getSurveyCount();

    /**
     * Set Survey average overall rating
     *
     * @param integer $$surveyAverageOverallRating
     * @return TypeServiceEntityInterface
     */
    public function setSurveyAverageOverallRating($surveyAverageOverallRating);

    /**
     * Get Survey average overall rating
     *
     * @return integer
     */
    public function getSurveyAverageOverallRating();

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return TypeServiceEntityInterface
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
     * @param  \DateTime $updatedAt
     * @return TypeServiceEntityInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * General locale field getter
     *
     * @param string $field
     * @param string $locale
     * @param array $allowedLocales
     * @return string
     */
    public function getLocaleField($field, $locale, $allowedLocales);
}