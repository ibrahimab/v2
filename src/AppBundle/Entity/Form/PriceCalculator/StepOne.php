<?php
namespace AppBundle\Entity\Form\PriceCalculator;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class StepOne
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $place;

    /**
     * @var array
     */
    public $persons;

    /**
     * @var integer
     */
    public $person;

    /**
     * @var array
     */
    public $weekends;

    /**
     * @var integer
     */
    public $weekend;
}