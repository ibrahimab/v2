<?php
namespace AppBundle\Service\Api\Place;

use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;

/**
 * This is the PlaceService, with this service you can manipulate places
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class PlaceService
{
    /**
     * @var PlaceServiceRepositoryInterface
     */
    private $placeServiceRepository;

    /**
     * Constructor
     *
     * @param PlaceServiceRepositoryInterface $placeServiceRepository
     */
    public function __construct(PlaceServiceRepositoryInterface $placeServiceRepository)
    {
        $this->placeServiceRepository = $placeServiceRepository;
    }

    /**
     * Fetch all the places
     *
     * Fetching all the places based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return PlaceServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->placeServiceRepository->all($options);
    }

    /**
     * Finding a single place, based on criteria passed in
     *
     * @param array $by
     * @return PlaceServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->placeServiceRepository->find($by);
    }

    /**
     * Finding a single place, based on criteria passed in
     *
     * @param string $seoName
     * @param string $locale
     * @return PlaceServiceEntityInterface
     */
    public function findByLocaleSeoName($seoName, $locale)
    {
        return $this->placeServiceRepository->findByLocaleSeoName($seoName, $locale);
    }

    /**
     * Getting places flagged as 'homepage' place
     *
     * @param RegionServiceEntityInterface $region
     * @param array $options
     * @return PlaceServiceEntityInterface[]
     */
    public function findHomepagePlaces(RegionServiceEntityInterface $region, $options = [])
    {
        return $this->placeServiceRepository->findHomepagePlaces($region, $options);
    }

    /**
     * @param integer $placeId
     * @param integer $limit
     *
     * @return array
     */
    public function getTypes($placeId, $limit)
    {
        return $this->placeServiceRepository->getTypes($placeId, $limit);
    }
}
