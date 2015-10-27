<?php
namespace AppBundle\Entity\Form\PriceCalculator;

use       AppBundle\Entity\Form\PriceCalculator\OptionGroup;
use       AppBundle\Entity\Form\PriceCalculator\Option;
use       Doctrine\Common\Collections\ArrayCollection;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class StepTwo
{
    /**
     * @var array
     */
    public $options;
    
    /**
     * @var integer
     */
    public $person;
    
    
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options, $person)
    {
        $this->options = new ArrayCollection();
        
        foreach ($options as $group) {
            
            $groupEntity          = new OptionGroup;
            $groupEntity->groupId = (int)$group['groupId'];
            $groupEntity->name    = $group['name'];
            $groupEntity->parts   = new ArrayCollection();
            
            foreach ($group['parts'] as $part) {
                
                $partEntity           = new Option;
                $partEntity->id       = (int)$part['id'];
                $partEntity->name     = $part['name'];
                $partEntity->price    = (float)$part['price'];
                $partEntity->discount = (bool)$part['discount'];
                $partEntity->free     = (bool)$part['free'];
                $partEntity->person   = (int)$person;
                
                $groupEntity->parts->add($partEntity);
            }
            
            $this->options->add($groupEntity);
        }
    }
}