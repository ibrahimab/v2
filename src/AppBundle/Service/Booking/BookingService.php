<?php
namespace AppBundle\Service\Booking;

use       AppBundle\Entity\Booking\Booking                  as BookingEntity;
use       AppBundle\Service\Api\Booking\BookingService      as BookingDataService;
use       AppBundle\Service\Api\Accommodation\Accommodation as AccommodationService;

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
    }

    /**
     * @param BookingEntity
     */
    public function create(BookingEntity $booking)
    {
        $accommodation = $this->accommodationService->accinfo($booking->getTypeId());
        dump($accommodation);exit;
        $this->bookingDataService->create($booking);
        // $this->bookingDataService->
    }
}