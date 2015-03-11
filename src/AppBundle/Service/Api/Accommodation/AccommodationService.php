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
     * @return AcommodationServiceEntityInterface
     */
    public function all($options = [])
    {
        return $this->accommodationRepository->all($options);
    }
    
    /**
     * Get a single accommodation with certain criteria defined on the $by parameter
     *
     * @param  array $by
     * @return AcommodationServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->accommodationRepository->find($by);
    }
    
    public function clean()
    {
        $this->accommodationRepository->clean();
    }
}