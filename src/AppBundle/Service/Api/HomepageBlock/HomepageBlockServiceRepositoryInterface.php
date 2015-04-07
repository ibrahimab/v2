<?php
namespace AppBundle\Service\Api\HomepageBlock;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface HomepageBlockServiceRepositoryInterface
{
    /**
     * Fetching homepage blocks
     *
     * Fetching all the homepage blocks based on the options passed in. The supported options are: 'where', 'order', 'limit', 'offset'
     *
     * @param array $options
     * @return HomepageBlockServiceEntityInterface[]
     */
    public function all($options = []);
    
    /**
     * Finding a single homepage blocks
     *
     * @param array $by
     * @return HomepageBlockServiceEntityInterface
     */
    public function find($by = []);
    
    /**
     * Finding published blocks
     *
     * @param array $options
     * @return HomepageBlockServiceEntityInterface[]
     */
    public function published($options = []);
}