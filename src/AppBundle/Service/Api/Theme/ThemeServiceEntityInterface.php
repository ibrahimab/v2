<?php
namespace AppBundle\Service\Api\Theme;
use       AppBundle\Service\Api\Theme\ThemeServiceEntityInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface ThemeServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set season
     *
     * @param  int $season
     * @return ThemeServiceEntityInterface
     */
    public function setSeason($season);

    /**
     * Get season
     *
     * @return int
     */
    public function getSeason();

    /**
     * Setting active flag
     *
     * @param integer $active
     * @return ThemeServiceEntityInterface
     */
    public function setActive($active);

    /**
     * Get active flag
     *
     * @return integer
     */
    public function getActive();

    /**
     * Set accommodation feature
     *
     * @param  int $accommodationFeature
     * @return ThemeServiceEntityInterface
     */
    public function setAccommodationFeature($accommodationFeature);

    /**
     * Get accommodation Feature
     *
     * @return int
     */
    public function getAccommodationFeature();

    /**
     * Set Name
     *
     * @param string $name
     * @return ThemeServiceEntityInterface
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set English Name
     *
     * @param string $englishName
     * @return ThemeServiceEntityInterface
     */
    public function setEnglishName($englishName);

    /**
     * Get english name
     *
     * @return string
     */
    public function getEnglishName();

    /**
     * Set German Question
     *
     * @param string $germanQuestion
     * @return ThemeServiceEntityInterface
     */
    public function setGermanName($germanName);

    /**
     * Get german name
     *
     * @return string
     */
    public function getGermanName();

    /**
     * Set Locale Names
     *
     * @param array $localeNames
     * @return ThemeServiceEntityInterface
     */
    public function setLocaleNames($localeNames);

    /**
     * Get locale name
     *
     * @return string
     */
    public function getLocaleName($locale);

    /**
     * Set Url
     *
     * @param string $url
     * @return ThemeServiceEntityInterface
     */
    public function setUrl($url);

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set English url
     *
     * @param string $englishUrl
     * @return ThemeServiceEntityInterface
     */
    public function setEnglishUrl($englishUrl);

    /**
     * Get english url
     *
     * @return string
     */
    public function getEnglishUrl();

    /**
     * Set German Url
     *
     * @param string $germanUrl
     * @return ThemeServiceEntityInterface
     */
    public function setGermanUrl($germanUrl);

    /**
     * Get german url
     *
     * @return string
     */
    public function getGermanUrl();

    /**
     * Set Locale Urls
     *
     * @param string $localeUrls
     * @return ThemeServiceEntityInterface
     */
    public function setLocaleUrls($localeUrls);

    /**
     * Get locale url
     *
     * @return string
     */
    public function getLocaleUrl($locale);

    /**
     * Set External Url
     *
     * @param string $externalUrl
     * @return ThemeServiceEntityInterface
     */
    public function setExternalUrl($externalUrl);

    /**
     * Get External url
     *
     * @return string
     */
    public function getExternalUrl();

    /**
     * Set English External url
     *
     * @param string $englishExternalUrl
     * @return ThemeServiceEntityInterface
     */
    public function setEnglishExternalUrl($englishExternalUrl);

    /**
     * Get english External url
     *
     * @return string
     */
    public function getEnglishExternalUrl();

    /**
     * Set German External Url
     *
     * @param string $germanExternalUrl
     * @return ThemeServiceEntityInterface
     */
    public function setGermanExternalUrl($germanExternalUrl);

    /**
     * Get german url
     *
     * @return string
     */
    public function getGermanExternalUrl();

    /**
     * Set Locale External Urls
     *
     * @param string $localeExternalUrls
     * @return ThemeServiceEntityInterface
     */
    public function setLocaleExternalUrls($localeExternalUrls);

    /**
     * Get locale External url
     *
     * @return string
     */
    public function getLocaleExternalUrl($locale);

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