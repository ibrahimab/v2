<?php
namespace AppBundle\Service\PriceCalculator;

use       AppBundle\Service\PriceCalculator\FormService;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;

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
      * @var PlaceServiceEntityInterface
      */
     private $place;

     /**
      * @var integer
      */
     private $person;

     /**
      * @var integer
      */
     private $weekend;


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
 }