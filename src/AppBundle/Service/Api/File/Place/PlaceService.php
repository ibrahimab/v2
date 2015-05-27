<?php
namespace AppBundle\Service\Api\File\Place;
use		  AppBundle\Service\Api\Place\PlaceServiceEntityInterface;

class PlaceService
{
	const PLACE_IMAGE = 'plaatsen';

	private $placeServiceRepository;

	public function __construct(PlaceServiceRepositoryInterface $placeServiceRepository)
	{
		$this->placeServiceRepository = $placeServiceRepository;
	}

	public function getImage(PlaceServiceEntityInterface $place)
	{
		return $this->placeServiceRepository->getImage($place);
	}
}