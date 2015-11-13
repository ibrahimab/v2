<?php
namespace AppBundle\Service\Booking;

use       AppBundle\Entity\Booking\Booking as BookingEntity;
use       AppBundle\Service\Api\Booking\BookingService as BookingDataService;

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
     * Constructor
     *
     * @param BookingDataService $bookingDataService
     */
    public function __construct(BookingDataService $bookingDataService)
    {
        $this->bookingDataService = $bookingDataService;
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
        $accommodation = \accinfo($booking->getTypeId());
        dump($accommodation);exit;

        $this->bookingDataService->create($booking);
        // $this->bookingDataService->
    }
}