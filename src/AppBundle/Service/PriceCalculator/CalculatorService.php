<?php
namespace AppBundle\Service\PriceCalculator;

use       AppBundle\Service\PriceCalculator\FormService;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * PriceCalculatorService
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package chalet
 * @version 0.2.7
 * @since   0.2.7
 */
 class CalculatorService
 {
     /**
      * @var FormService
      */
     private $formService;

     /**
      * @var TypeServiceEntityInterface
      */
     private $type;

     /**
      * @var integer
      */
     private $person;

     /**
      * @var integer
      */
     private $weekend;
     
     /**
      * @var array
      */
     private $options;
     
     /**
      * @var array
      */
     private $insurances;
     
     /**
      * @var array
      */
     private $percentages;
     
     /**
      * @var float
      */
     private $policyCosts;


     /**
      * Constructor
      *
      * @param FormService $formService
      */
     public function __construct(FormService $formService)
     {
         $this->formService = $formService;
     }

     /**
      * @return FormService
      */
     public function getFormService()
     {
         if (null === $this->formService->getCalculatorService()) {
             $this->formService->setCalculatorService($this);
         }
         
         return $this->formService;
     }

     /**
      * @param  integer           $typeId
      * @return CalculatorService
      */
     public function setType(TypeServiceEntityInterface $type)
     {
         $this->type = $type;
         return $this;
     }

     /**
      * @return TypeServiceEntityInterface
      */
     public function getType()
     {
         return $this->type;
     }
     
     /**
      * @param PlaceServiceEntityInterface $place
      * @return CalculatorService
      */
     public function setPlace(PlaceServiceEntityInterface $place)
     {
         $this->place = $place;
         return $this;
     }
     
     /**
      * @return PlaceServiceEntityInterface
      */
     public function getPlace()
     {
         return $thiss;
     }

     /**
      * @param integer            $person
      * @return CalculatorService
      */
     public function setPerson($person)
     {
         $this->person = $person;
         return $this;
     }

     /**
      * @return integer
      */
     public function getPerson()
     {
         return $this->person;
     }
     
     /**
      * @param  $persons
      * @return CalculatorService
      */
     public function setPersons($persons)
     {
         $this->persons = $persons;
         return $this;
     }
     
     public function getPersons()
     {
         return $this->persons;
     }

	 /**
	  * @param integer 			  $weekend
	  * @return CalculatorService
	  */
	 public function setWeekend($weekend)
	 {
	 	$this->weekend = $weekend;
		return $this;
	 }

	 /**
	  * @return integer
	  */
	 public function getWeekend()
	 {
	 	return $this->weekend;
    }
    
    /**
     * @param  array       $weekends
     * @return FormService
     */
    public function setWeekends($weekends)
    {
        $this->weekends = $weekends;
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getWeekends()
    {
        return $this->weekends;
    }
    
    /**
     * @param  array             $options
     * @return CalculatorService
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * @param  array $insurances
     * @return CalculatorService
     */
    public function setCancellationInsurances($insurances)
    {
        $this->insurances = array_filter($insurances, function($insurance) {
            return (true === $insurance['active']);
        });
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getCancellationInsurances()
    {
        return $this->insurances;
    }
    
    /**
     * @param  array             $percentages
     * @return CalculatorService
     */
    public function setCancellationPercentages($percentages)
    {
        $this->percentages = $percentages;
        return $this;
    }
    
    /**
     * @return array
     */
    public function getCancellationPercentages()
    {
        return $this->percentages;
    }
    
    /**
     * @param  float             $policyCosts
     * @return CalculatorService
     */
    public function setPolicyCosts($policyCosts)
    {
        $this->policyCosts = $policyCosts;
        return $this;
    }
    
    /**
     * @return float
     */
    public function getPolicyCosts()
    {
        return $this->policyCosts;
    }
 }