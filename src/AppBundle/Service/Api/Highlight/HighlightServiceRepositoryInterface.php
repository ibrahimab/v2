<?php
namespace AppBundle\Service\Api\Highlight;

/**
 * To use the Highlight Service, one must provide a repository that implements this interface
 */
interface HighlightServiceRepositoryInterface
{
    /**
     * Get all highlights
     *
     * @param array $options
     * @return HighlightServiceEntityInterface[]
     */
    public function all($options = []);
    
    /**
     * Get one highlight based on certain criteria
     *
     * @param  array $by
     * @return HighlightServiceEntityInterface|null
     */
    public function find($by = []);
}