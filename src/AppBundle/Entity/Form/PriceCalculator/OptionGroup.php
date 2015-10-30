<?php
namespace AppBundle\Entity\Form\PriceCalculator;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class OptionGroup
{
    /**
     * @var integer
     */
    public $groupId;
    
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var array
     */
    public $parts;
}