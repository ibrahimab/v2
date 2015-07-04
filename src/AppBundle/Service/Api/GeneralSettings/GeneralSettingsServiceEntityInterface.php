<?php
namespace AppBundle\Service\Api\GeneralSettings;

/**
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface GeneralSettingsServiceEntityInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set winterNewsletters
     *
     * @param string $winterNewsletters
     * @return GeneralSettingsServiceEntityInterface
     */
    public function setWinterNewsletters($winterNewsletters);

    /**
     * Get winterNewsletters
     *
     * @return string
     */
    public function getWinterNewsletters();

    /**
     * Set winterNewslettersBelgium
     *
     * @param string $winterNewslettersBelgium
     * @return GeneralSettingsServiceEntityInterface
     */
    public function setWinterNewslettersBelgium($winterNewslettersBelgium);

    /**
     * Get winterNewslettersBelgium
     *
     * @return string
     */
    public function getWinterNewslettersBelgium();

    /**
     * Set summerNewsletters
     *
     * @param string $summerNewsletters
     * @return GeneralSettingsServiceEntityInterface
     */
    public function setSummerNewsletters($summerNewsletters);

    /**
     * Get summerNewsletters
     *
     * @return string
     */
    public function getSummerNewsletters();

    /**
     * Set summerNewslettersBelgium
     *
     * @param string $summerNewslettersBelgium
     * @return GeneralSettingsServiceEntityInterface
     */
    public function setSummerNewslettersBelgium($summerNewslettersBelgium);

    /**
     * Get summerNewslettersBelgium
     *
     * @return string
     */
    public function getSummerNewslettersBelgium();

    /**
     * Set Monitor MySQL value
     *
     * @param string $monitorMySQL
     * @return GeneralSettingsServiceEntityInterface
     */
    public function setMonitorMySQL($monitorMySQL);

    /**
     * Get monitorMySQL value
     *
     * @return string
     */
    public function getMonitorMySQL();
}