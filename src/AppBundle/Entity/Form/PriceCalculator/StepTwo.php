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
     * @var integer
     */
    public $weekend;

    /**
     * @var integer
     */
    public $booking;

    /**
     * @var array
     */
    public $cancellation_insurances;

    /**
     * @var array
     */
    public $damage_insurance;


    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct($options, $person, $weekend, $booking, $insurances, $percentages, $policyCosts)
    {
        $this->options                 = [];
        $this->person                  = $person;
        $this->weekend                 = $weekend;
        $this->booking                 = $booking;
        $this->cancellation_insurances = [];
        $this->percentages             = $percentages;
        $this->policyCosts             = $policyCosts;

        foreach ($options as $groupId => $group) {

            $groupEntity          = new OptionGroup;
            $groupEntity->groupId = $groupId;
            $groupEntity->name    = $group['naam_enkelvoud'];
            $groupEntity->parts   = [];

            foreach ($group['onderdelen'] as $partId => $part) {

                $partEntity                  = new Option;
                $partEntity->id              = $partId;
                $partEntity->name            = $part['naam'];
                $partEntity->person          = (int)$person;
                $partEntity->price           = abs($price = (float)$part['verkoop']);
                $partEntity->commission      = (float)$part['commissie'];
                $partEntity->discount        = $price < 0;
                $partEntity->free            = $price === 0.0;
                $partEntity->minAge          = (int)$part['min_leeftijd'];
                $partEntity->maxAge          = (int)$part['max_leeftijd'];
                $partEntity->minParticipants = (int)$part['min_deelnemers'];

                $groupEntity->parts[$partEntity->id] = $partEntity;
            }

            $this->options[$groupEntity->groupId] = $groupEntity;
        }

        foreach ($insurances as $insurance) {

            $insuranceEntity              = new CancellationInsurance;
            $insuranceEntity->id          = (int)$insurance['id'];
            $insuranceEntity->name        = $insurance['name'];
            $insuranceEntity->person      = (int)$person;
            $insuranceEntity->percentages = $percentages;
            $insuranceEntity->policyCosts = $policyCosts;

            $this->cancellation_insurances[$insuranceEntity->id] = $insuranceEntity;
        }
    }
}