<?php
namespace AppBundle\Entity\PriceCalculator;

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
     * @var integer
     */
    public $type_id;
    
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
    
    
    /**
     * Constructor
     */
    public function __construct($data)
    {
        $this->type     = $data['type'];
        $this->type_id  = $data['type_id'];
        $this->person   = $data['person'];
        $this->persons  = $data['persons'];
        $this->weekend  = $data['weekend'];
        $this->weekends = $data['weekends'];
    }
}