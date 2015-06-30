<?php
namespace AppBundle\Service\Api\GeneralSettings;

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
     * @return GeneralSettings
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
     * @return GeneralSettings
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
     * @return GeneralSettings
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
     * @return GeneralSettings
     */
    public function setSummerNewslettersBelgium($summerNewslettersBelgium);

    /**
     * Get summerNewslettersBelgium
     *
     * @return string
     */
    public function getSummerNewslettersBelgium();

}