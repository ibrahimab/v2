<?php
namespace AppBundle\Entity\Form\PriceCalculator;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class CancellationInsurance
{
    /**
     * @var integer
     */
    public $id;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var integer
     */
    public $person;
    
    /**
     * @var integer
     */
    public $amount;
    
    /**
     * @var array
     */
    public $percentages;
    
    /**
     * @var float
     */
    public $policyCosts;
}