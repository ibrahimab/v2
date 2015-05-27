<?php
namespace AppBundle\Service\Api\Booking;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;

/**
 * BookingServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface BookingServiceRepositoryInterface
{
    /**
     * Setting season
     * 
     * @param SeasonConcern $seasonConcern
     * @return void
     */
    public function setSeason(SeasonConcern $seasonConcern);
    
    /**
     * Getting season
     *
     * @return integer
     */
    public function getSeason();
    
    /**
     * Setting website
     * 
     * @param WebsiteConcern $seasonConcern
     * @return void
     */
    public function setWebsite(WebsiteConcern $websiteConcern);
    
    /**
     * Getting website
     *
     * @return integer
     */
    public function getWebsite();
        
    /**
     * This method selects all the bookings based on criteria
     *
     * @param  array $options
     * @return BookingServiceRepositoryInterface[]
     */
    public function all($options  = []);
    
    /**
     * Select a single booking with a flag (be it any field the booking has)
     *
     * @param  array $by
     * @return BookingServiceRepositoryInterface|null
     */
    public function find($by = []);
}