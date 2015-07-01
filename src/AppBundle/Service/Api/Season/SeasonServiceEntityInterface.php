<?php
namespace AppBundle\Service\Api\Season;

interface SeasonServiceEntityInterface
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
     * @param  string $name
     * @return SeasonServiceEntityInterface
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
     * @return SeasonServiceEntityInterface
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
     * @return SeasonServiceEntityInterface
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
     * @return SeasonServiceEntityInterface
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
     * @param  boolean $display
     * @return SeasonServiceEntityInterface
     */
    public function setDisplay($display);

    /**
     * Get display
     *
     * @return boolean
     */
    public function getDisplay();

    /**
     * Set season
     *
     * @param  int $season
     * @return SeasonServiceEntityInterface
     */
    public function setSeason($season);

    /**
     * Get season
     *
     * @return int
     */
    public function getSeason();

    /**
     * Set insurance policy costs
     *
     * @param  float $insurancesPolicyCosts
     * @return SeasonServiceEntityInterface
     */
    public function setInsurancesPolicyCosts($insurancesPolicyCosts);

    /**
     * Get insurance policy costs
     *
     * @return float
     */
    public function getInsurancesPolicyCosts();

    /**
     * Set start
     *
     * @param  \DateTime $start
     * @return SeasonServiceEntityInterface
     */
    public function setStart($start);

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart();

    /**
     * Set end
     *
     * @param  \DateTime $end
     * @return SeasonServiceEntityInterface
     */
    public function setEnd($end);

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd();

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