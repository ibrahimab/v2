<?php
namespace AppBundle\Service\Api\File\Accommodation;
use		  AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;

class AccommodationService
{
	const MAIN_IMAGE  = 'hoofdfoto_accommodatie';
	const BELOW_IMAGE = 'accommodaties_aanvullend_onderaan';

	private $accommodationServiceRepository;

	public function __construct(AccommodationServiceRepositoryInterface $accommodationServiceRepository)
	{
		$this->accommodationServiceRepository = $accommodationServiceRepository;
	}

	public function getMainImage(AccommodationServiceEntityInterface $accommodation)
	{
		return $this->accommodationServiceRepository->getMainImage($accommodation);
	}

	public function getImages(AccommodationServiceEntityInterface $accommodation)
	{
		return $this->accommodationServiceRepository->getImages($accommodation);
	}
}