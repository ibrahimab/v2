<?php
namespace AppBundle\Entity\Place;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Place\PlaceServiceRepositoryInterface;

/**
 * PlaceRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class PlaceRepository extends BaseRepository implements PlaceServiceRepositoryInterface
{
}