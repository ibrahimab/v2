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
     * @return RegionServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->regionServiceRepository->all($options);
    }
    
    /**
     * Finding a single region, based on criteria passed in
     *
     * @param array $by
     * @return RegionServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->regionServiceRepository->find($by);
    }
    
    /**
     * This method fetches a region based on a locale field,
     * Because of certain schema design decisions of the old website
     * regions do 
     * 
     *
     * @param string $field
     * @param string $value
     * @param string $locale
     * @return array [0 => RegionServiceEntityInterface, 1 => PlaceServiceEntityInterface]
     */
    public function findByLocaleName($name, $locale)
    {
        return $this->regionServiceRepository->findByLocaleName($name, $locale);
    }
    
    /**
     * Find region by its seo name, with locale in mind
     *
     * @param string $seoName
     * @param string $locale
     * @return RegionServiceEntityInterface
     */
    public function findByLocaleSeoName($seoName, $locale)
    {
        return $this->regionServiceRepository->findByLocaleSeoName($seoName, $locale);
    }
    
    /**
     * Find random regions with the homepage flag on
     *
     * @param array $options
     * @return RegionServiceEntityInterface
     */
    public function findHomepageRegions($options = [])
    {
        return $this->regionServiceRepository->findHomepageRegions($options);
    }
}