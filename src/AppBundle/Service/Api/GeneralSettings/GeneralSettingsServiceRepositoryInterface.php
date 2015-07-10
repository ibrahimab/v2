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
     * Fetching newsletters
     *
     *
     * @return GeneralSettingsServiceEntityInterface[]
     */
    public function getNewsletters();

    /**
     * Monitoring database
     *
     * @return boolean
     */
    public function monitorDatabase();
}