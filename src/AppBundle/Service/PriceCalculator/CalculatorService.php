<?php
namespace AppBundle\Service\PriceCalculator;
use       AppBundle\Service\PriceCalculator\FormService;
use       AppBundle\Service\Api\Type\TypeService;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Option\OptionService;

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
      * @var TypeServiceEntityInterface
      */
     private $type;
     
     /**
      * @var integer
      */
     private $typeId;
     
     /**
      * @var TypeService
      */
     private $typeService;
     
     /**
      * @var FormService
      */
     private $formService;
     
     /**
      * @param ContainerInterface $container
      */
     public function __construct(TypeService $typeService, OptionService $optionService, FormService $formService)
     {
         $this->typeService   = $typeService;
         $this->optionService = $optionService;
     }
     
     /**
      * @param integer $typeId
      * @return PriceCalculatorService
      */
     public function setTypeId($typeId)
     {
         $this->typeId = $typeId;
     }
     
     /**
      * @return TypeServiceEntityInterface
      */
     public function type()
     {
         if (null === $this->type) {
             $this->type = $this->typeService->find(['id' => $this->typeId]);
         }
         
         return $this->type;
     }
     
     /**
      * @return array
      */
     public function options()
     {
         if (null === $this->options) {
             $this->options = $this->optionService->options($this->type()->getId());
         }
         
         return $this->options;
     }
     
     public function form()
     {
         if (null === $this->form) {
             $this->form = $this->formService->stepOneForm();
         }
     }
 }