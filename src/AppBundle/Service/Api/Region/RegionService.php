<?php
namespace AppBundle\Service\Api\Region;

/**
 * This is the RegionService, with this service you can manipulate regions
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class RegionService
{
    /**
     * @var RegionServiceRepositoryInterface
     */
    private $regionServiceRepository;
    
    /**
     * Constructor
     *
     * @param RegionServiceRepositoryInterface $regionServiceRepository
     */
    public function __construct(RegionServiceRepositoryInterface $regionServiceRepository)
    {
        $this->regionServiceRepository = $regionServiceRepository;
    }
    
    /**
     * Fetch all the regions
     *
     * Fetching all the regions based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     */
    public function all($options = [])
    {
        return $this->regionServiceRepository->all($options);
    }
    
    /**
     * Finding a single region, based on criteria passed in
     *
     * @param array $by
     */
    public function find($by = [])
    {
        return $this->regionServiceRepository->find($by);
    }
    
    /**
     * Find region by its name, with locale in mind
     */
    public function findByLocaleName($name, $locale)
    {
        return $this->regionServiceRepository->findByLocaleName($name, $locale);
    }
}