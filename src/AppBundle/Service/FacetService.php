<?php
namespace AppBundle\Service;
use       AppBundle\Service\Paginator\PaginatorService;

/**
 * FacetService
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class FacetService
{
    /**
     * @var array
     */
    private $filters;
    
    /**
     * @var PaginatorService
     */
    private $paginator;
    
    /**
     * Constructor
     *
     * @param array $filters
     */
    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    
    /**
     * Calculate the facets
     *
     * @return array
     */
    public function calculate()
    {
        foreach ($this->filters as $filter => $values) {
            
            foreach ($values as $value) {
                
                
            }
        }
    }
}