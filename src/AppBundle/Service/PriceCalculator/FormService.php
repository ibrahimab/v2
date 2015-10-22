<?php
namespace AppBundle\Service\PriceCalculator;
use       AppBundle\Form\PriceCalculator\StepOneForm;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class FormService
{
    /**
     * @param StepOneForm $stepOne
     */
    public function __construct(StepOneForm $stepOne)
    {
        $this->stepOne = $stepOne;
    }
}