<?php
namespace AppBundle\Service\Api\Place;

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
     */
    public function all($options = [])
    {
        return $this->placeServiceRepository->all($options);
    }
    
    /**
     * Finding a single place, based on criteria passed in
     *
     * @param array $by
     */
    public function find($by = [])
    {
        return $this->placeServiceRepository->find($by);
    }
}