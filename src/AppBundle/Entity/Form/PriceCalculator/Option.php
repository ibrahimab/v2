<?php
namespace AppBundle\Entity\Form\PriceCalculator;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.2.7
 * @since   0.2.7
 */
class Option
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
     * @var float
     */
    public $price;

    /**
     * @var float
     */
    public $commission;

    /**
     * @var boolean
     */
    public $discount;

    /**
     * @var boolean
     */
    public $free;

    /**
     * @var integer
     */
    public $amount;

    /**
     * @var integer
     */
    public $person;

    /**
     * @var integer
     */
    public $minAge;

    /**
     * @var integer
     */
    public $maxAge;

    /**
     * @var integer
     */
    public $minParticipants;
}