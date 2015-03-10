<?php
namespace AppBundle\Service\Api\Accommodation;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceRepositoryInterface;

class AccommodationService
{    
    /**
     * @var AccommodationServiceRepositoryInterface
     */
    private $accommodationRepository;
    
    /**
     * Constructor
     * 
     * @param AccommodationServiceRepositoryInterface $accommodationRepository
     */
    public function __construct(AccommodationServiceRepositoryInterface $accommodationRepository)
    {
        $this->accommodationRepository = $accommodationRepository;
    }
    
    /**
     * Get all accommodations
     * The options array accepts a 'where', 'order' and 'limit' key that customizes
     * the query
     *
     * @param  array $options
     * @return Accommodation
     */
    public function all($options = [])
    {
        return $this->accommodationRepository->all($options);
    }
    
    public function find($by)
    {
        return $this->accommodationRepository->find($by);
    }
}