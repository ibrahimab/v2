<?php
namespace AppBundle\Service\Api\HomepageBlock;

/**
 * This is the HomepageBlocksService, with this service you can work with homepage blocks
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class HomepageBlockService
{
    /**
     * @var HomepageBlockServiceRepositoryInterface
     */
    private $homepageBlockServiceRepository;
    
    /**
     * Constructor
     *
     * @param HomepageBlockServiceRepositoryInterface $homepageBlockServiceRepository
     */
    public function __construct(HomepageBlockServiceRepositoryInterface $homepageBlockServiceRepository)
    {
        $this->homepageBlockServiceRepository = $homepageBlockServiceRepository;
    }
    
    /**
     * Fetch all the homepage blocks
     *
     * Fetching all the homepage blocks based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return HomepageBlockServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->homepageBlockServiceRepository->all($options);
    }
    
    /**
     * Find single homepage block
     *
     * @param array $by
     * @return HomepageBlockServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->homepageBlockServiceRepository->find($by);
    }
    
    /**
     * Find published blocks
     *
     * @param array $options
     * @return HomepageBlockServiceEntityInterface[]
     */
    public function published($options = [])
    {
        return $this->homepageBlockServiceRepository->published($options);
    }
}