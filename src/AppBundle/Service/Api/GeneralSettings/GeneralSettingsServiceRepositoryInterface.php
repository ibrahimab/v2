<?php
namespace AppBundle\Service\Api\GeneralSettings;


/**
 * GeneralSettingsServiceRepositoryInterface
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface GeneralSettingsServiceRepositoryInterface
{

    /**
     * Finding all previously sent newsletters
     *
     * @return array
     */
    public function getNewsletters();

    /**
     * Finding if message "search without dates" has to be shown on search form
     *
     *
     * @return boolean
     */
    public function getSearchFormMessageSearchWithoutDates();

    /**
     * Monitoring database
     *
     * @return boolean
     */
    public function monitorDatabase();

    /**
     * Get what to show when there are no prices for an accommodation
     *
     * @return boolean
     */
    public function getNoPriceShowUnavailable();

}
