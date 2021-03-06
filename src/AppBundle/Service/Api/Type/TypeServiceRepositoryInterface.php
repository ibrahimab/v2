<?php
namespace AppBundle\Service\Api\Type;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;

/**
 * TypeServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @version 0.0.5
 * @since   0.0.1
 * @package Chalet
 */
interface TypeServiceRepositoryInterface
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
     * This method selects all the types based on certain options
     *
     * @param  array $options
     * @return TypeServiceEntityInterface[]
     */
    public function all($options  = []);

    /**
     * Select a single type with a flag (be it any field the type has)
     *
     * @param  array $by
     * @return TypeServiceEntityInterface|null
     */
    public function find($by = []);

    /**
     * Select types by place
     *
     * @param  PlaceServiceEntityInterface $place
     * @param  integer $limit
     * @return TypeServiceEntityInterface[]
     */
    public function findByPlace(PlaceServiceEntityInterface $place, $limit);

    /**
     * Counting types of all the accommodations for place given, returns count
     *
     * @param PlaceServiceEntityInterface $place
     * @return integer
     */
    public function countByPlace(PlaceServiceEntityInterface $place);

    /**
     * Counting types of all the accommodations for region given, returns count
     *
     * @param RegionServiceEntityInterface $region
     * @return integer
     */
    public function countByRegion(RegionServiceEntityInterface $region);

    /**
     * Counting types of all the accommodations for every region given, returns array with region ID as its key and the count as its value
     *
     * @param RegionServiceEntityInterface[] $regions
     * @return array
     */
    public function countByRegions($regions);

    /**
     * Getting type and its associations by Id
     *
     * @param integer $typeId
     * @return TypeServiceEntityInterface
     * @deprecated
     */
    public function findById($typeId);

    /**
     * Getting type and returning array
     *
     * @param integer|array
     *
     * @return array
     */
    public function getTypeById($typeId);
}
