<?php
namespace AppBundle\Concern;

/**
 * AdditionalCostsConcern
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class AdditionalCostsConcern
{   
    /**
     * @const int
     */
    const ADDITIONAL_COSTS_CASH            = 1;
    
    /**
     * @const int
     */
    const ADDITIONAL_COSTS_CREDITCARD      = 2;
    
    /**
     * @const int
     */
    const ADDITIONAL_COSTS_CASH_CREDITCARD = 3;
    
    /**
     * @const int
     */
    const ADDITIONAL_COSTS_NOT_APPLICABLE  = 4;
    
    /**
     * @const int
     */
    const ADDITIONAL_COSTS_AMOUNT_UNKNOWN  = 5;
    
    /**
     * @const int
     */
    const ADDITIONAL_COSTS_PAID_IN_ADVANCE = 6;
    
    /**
     * @const int
     */
    const UNIT_PER_STAY                    = 1;
    const UNIT_PER_PERSON                  = 2;
    const UNIT_PER_DAY                     = 3;
    const UNIT_EACH                        = 4;
    const UNIT_PER_KWH                     = 5;
    const UNIT_PER_LITER                   = 6;
    const UNIT_PER_SET                     = 7;
    const UNIT_PER_WEEK                    = 8;
    const UNIT_PER_BAG                     = 9;
    const UNIT_PER_TIME                    = 10;
    const UNIT_PER_HOUR                    = 11;
    const UNIT_CUBIC_METERS                = 12;
    
    const LOCALLY_PAID_IN_ADVANCE          = 0;
    const LOCALLY_PAID_LOCALLY             = 1;
}