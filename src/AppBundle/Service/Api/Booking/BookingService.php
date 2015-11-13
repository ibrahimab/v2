<?php
namespace AppBundle\Service\Api\Booking;

/**
 * This is the BookingService, with this service you can manipulate bookings
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class BookingService
{
    /**
     * @var CountryServiceRepositoryInterface
     */
    private $bookingServiceRepository;

    /**
     * Constructor
     *
     * @param BookingServiceRepositoryInterface $bookingServiceRepository
     */
    public function __construct(BookingServiceRepositoryInterface $bookingServiceRepository)
    {
        $this->bookingServiceRepository = $bookingServiceRepository;
    }

    /**
     * Find all the bookings
     *
     * @param  array $options
     * @return BookingServiceEntityInterface[]
     */
    public function all($options = [])
    {
        return $this->bookingServiceRepository->all($options);
    }

    /**
     * Find a single booking
     *
     * @param  array $options
     * @return BookingServiceEntityInterface
     */
    public function find($by = [])
    {
        return $this->bookingServiceRepository->find($by);
    }

    /**
     * @param BookingServiceEntityInterface $booking
     * @return BookService
     */
    public function create($booking)
    {
        return $this->bookingServiceRepository->create($booking);
    }
}