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
     * @var array
     */
    public $cancellation_insurances;
    
    
    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options, $person, $insurances, $percentages, $policyCosts)
    {
        $this->options                 = new ArrayCollection();
        $this->person                  = $person;
        $this->cancellation_insurances = new ArrayCollection();
        $this->percentages             = $percentages;
        $this->policyCosts             = $policyCosts;
        
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
        
        foreach ($insurances as $insurance) {
            
            $insuranceEntity              = new CancellationInsurance;
            $insuranceEntity->id          = (int)$insurance['id'];
            $insuranceEntity->name        = $insurance['name'];
            $insuranceEntity->person      = (int)$person;
            $insuranceEntity->percentages = $percentages;
            $insuranceEntity->policyCosts = $policyCosts;
            
            $this->cancellation_insurances->add($insuranceEntity);
        }
    }
}