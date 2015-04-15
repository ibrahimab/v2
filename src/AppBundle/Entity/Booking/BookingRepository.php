<?php
namespace AppBundle\Entity\Booking;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Booking\BookingServiceRepositoryInterface;

/**
 * BookingRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class BookingRepository extends BaseRepository implements BookingServiceRepositoryInterface
{
}
