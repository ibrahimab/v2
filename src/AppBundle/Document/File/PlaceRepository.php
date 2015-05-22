<?php
namespace AppBundle\Document\File;
use		  AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\File\Place\PlaceService;
use		  AppBundle\Service\Api\File\Place\PlaceServiceRepositoryInterface;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * AccommodationRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class PlaceRepository extends DocumentRepository implements PlaceServiceRepositoryInterface
{
	public function getImage(PlaceServiceEntityInterface $place)
	{
		return $this->findOneBy(['file_id' => $place->getId(), 'kind' => PlaceService::PLACE_IMAGE, 'rank' => 1]);
	}
}