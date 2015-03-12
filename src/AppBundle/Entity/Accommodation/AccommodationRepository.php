<?php
namespace AppBundle\Entity\Accommodation;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Accommodation\AccommodationServiceRepositoryInterface;

/**
 * AccommodationRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class AccommodationRepository extends BaseRepository implements AccommodationServiceRepositoryInterface
{
}