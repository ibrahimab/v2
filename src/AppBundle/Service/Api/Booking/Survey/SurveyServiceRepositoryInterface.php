<?php
namespace AppBundle\Service\Api\Booking\Survey;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;

/**
 * SurveyServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface SurveyServiceRepositoryInterface
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
     * Get single survey by some criteria
     *
     * @param array $by
     * @return SurveyServiceEntityInterface
     */
    public function find($by = []);

    /**
     * Get all the surveys based on criteria passed in
     *
     * @param array options
     * @return SurveyServiceEntityInterface[]
     */
    public function all($options = []);

    /**
     * Get all the surveys based on criteria passed in
     *
     * @param  TypeServiceEntityInterface $type
     * @return SurveyServiceEntityInterface[]
     */
    public function allByType(TypeServiceEntityInterface $type);

    /**
     * @param TypeServiceEntityInterface $type
     * @return array
     */
    public function statsByType(TypeServiceEntityInterface $type);

    /**
     * @param TypeServiceEntityInterface[] $types
     * @return array
     */
    public function statsByTypes($types);

    /**
     * @param PlaceServiceEntityInterface $place
     * @return array
     */
    public function statsByPlace(PlaceServiceEntityInterface $place);

    /**
     * @param RegionServiceEntityInterface $region
     * @return array
     */
    public function statsByRegion(RegionServiceEntityInterface $region);

    /**
     * @param CountryServiceEntityInterface $country
     * @return array
     */
    public function statsByCountry(CountryServiceEntityInterface $country);
}
