<?php
namespace AppBundle\Service\Api\Accommodation;

use       AppBundle\Concern\FeatureConcern\FeatureConcernAccommodation;
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
     * Set name
     *
     * @param  string $name
     * @return AccommodationServiceEntityInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Get locale name
     *
     * @param array $localeNames
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
     */
    public function setLocaleDescriptions($localeDescriptions);

    /**
     * Get locale description
     *
     * @return string
     */
    public function getLocaleDescription($locale);

    /**
     * set season
     *
     * @param string $season
     * @return AccommodationServiceEntityInterface
     */
    public function setSeason($season);

    /**
     * Get season
     *
     * @return string
     */
    public function getSeason();

    /**
     * set layout
     *
     * @param string $layout
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
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
     * @return AccommodationServiceEntityInterface
     */
    public function setLocaleLayouts($germanLayout);

    /**
     * Get layout
     *
     * @return string
     */
    public function getLocaleLayout($locale);

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
     * Set display search flag
     *
     * @param boolean $displaySearch
     * @return AccommodationServiceEntityInterface
     */
    public function setDisplaySearch($displaySearch);

    /**
     * Get display search flag
     *
     * @return boolean
     */
    public function getDisplaySearch();

    /**
     * Set features
     *
     * @param FeatureConcernAccommodation $features
     * @return TypeServiceEntityInterface
     */
    public function setFeatures($features);

    /**
     * Get features
     *
     * @return FeatureConcernAccommodation
     */
    public function getFeatures();

    /**
     * Set quality
     *
     * @param  integer $quality
     * @return AccommodationServiceEntityInterface
     */
    public function setQuality($quality);

    /**
     * Get quality
     *
     * @return integer
     */
    public function getQuality();

    /**
     * Set search order
     *
     * @param  integer $searchOrder
     * @return AccommodationServiceEntityInterface
     */
    public function setSearchOrder($searchOrder);

    /**
     * Get searchOrder
     *
     * @return integer
     */
    public function getSearchOrder();

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