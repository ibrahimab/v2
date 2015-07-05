<?php
namespace AppBundle\Service\Api\Theme;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;

/**
 * ThemeServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @version 0.0.5
 * @since   0.0.5
 * @package Chalet
 */
interface ThemeServiceRepositoryInterface
{
    /**
     * Setting season
     *
     * @param SeasonConcern $seasonConcern
     * @return void
     */
    public function setSeason(SeasonConcern $seasonConcern);

    /**
     * Getting season
     *
     * @return integer
     */
    public function getSeason();

    /**
     * Setting website
     *
     * @param WebsiteConcern $seasonConcern
     * @return void
     */
    public function setWebsite(WebsiteConcern $websiteConcern);

    /**
     * Getting website
     *
     * @return integer
     */
    public function getWebsite();

    /**
     * This method selects all the themes based on certain options
     *
     * @param  array $options
     * @return ThemeServiceEntityInterface[]
     */
    public function all($options  = []);

    /**
     * Select a single theme with a optional where clause
     *
     * @param  array $by
     * @return ThemeServiceEntityInterface|null
     */
    public function find($by = []);

    /**
     * Select all active themes
     *
     * @return ThemeServiceEntityInterface[]
     */
    public function themes();
}