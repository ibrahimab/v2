<?php
namespace AppBundle\Old;
use       Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * RateTable wrapper
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class PricesWrapper
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * @var \vanafprijs
     */
    private $prices;
    
    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        
        $constants = $this->container->get('old.constants');
        $constants->setup();
        
        $this->prices = $this->container->get('old.prices');
    }
    
    public function get($typeIds)
    {
        $typeIds = (!is_array($typeIds) ? [$typeIds] : $typeIds);
        return $this->prices->get_vanafprijs(implode(', ', $typeIds));
    }
}