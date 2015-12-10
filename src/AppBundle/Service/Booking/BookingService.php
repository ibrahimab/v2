<?php
namespace AppBundle\Service\Booking;

use       AppBundle\Entity\Booking\Booking                          as BookingEntity;
use       AppBundle\Service\Api\Booking\BookingService              as BookingDataService;
use       AppBundle\Service\Api\Accommodation\AccommodationService;

/**
 * @author  Ibrahim Abdullah
 * @package Chalet
 */
class BookingService
{
    /**
     * @var BookingDataService
     */
    private $bookingDataService;

    /**
     * @var AccommodationService
     */
    private $accommodationService;

    /**
     * Constructor
     *
     * @param BookingDataService $bookingDataService
     */
    public function __construct(BookingDataService $bookingDataService, AccommodationService $accommodationService)
    {
        $this->bookingDataService   = $bookingDataService;
        $this->accommodationService = $accommodationService;
    }

    /**
     * @param BookingEntity $booking
     */
    public function setBooking(BookingEntity $booking)
    {
        $this->booking = $booking;
        return $this;
    }

    /**
     * @param BookingEntity
     */
    public function create($type)
    {
        return $this->bookingDataService->create($this->booking, $type);
    }

    /**
     * @param  integer $bookingId
     * @param  array   $insurances
     * @param  integer $persons
     * @param  array   $options
     *
     * @return
     */
    public function saveOptions($bookingId, $insurances, $persons, $options)
    {
        return $this->bookingDataService->saveOptions($bookingId, $insurances, $persons, $options);
    }
}