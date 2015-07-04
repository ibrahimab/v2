<?php
namespace AppBundle\Service\Api\GeneralSettings;

/**
 * This is the GeneralSettingsService, with this service you can work with homepage blocks
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @since   0.0.1
 * @package Chalet
 */
class GeneralSettingsService
{
    /**
     * @var GeneralSettingsServiceRepositoryInterface
     */
    private $GeneralSettingsServiceRepository;

    /**
     * Constructor
     *
     * @param GeneralSettingsServiceRepositoryInterface $GeneralSettingsServiceRepository
     */
    public function __construct(GeneralSettingsServiceRepositoryInterface $GeneralSettingsServiceRepository)
    {
        $this->generalSettingsServiceRepository = $GeneralSettingsServiceRepository;
    }

    /**
     * Fetch all the homepage blocks
     *
     * Fetching all the homepage blocks based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return GeneralSettingsServiceEntityInterface[]
     */
    public function getNewsletters()
    {
        return $this->generalSettingsServiceRepository->getNewsletters();
    }

    /**
     * Updating and selecting something from the database to see if it responds
     *
     * @return boolean
     */
    public function monitorDatabase()
    {
        return $this->generalSettingsServiceRepository->monitorDatabase();
    }
}