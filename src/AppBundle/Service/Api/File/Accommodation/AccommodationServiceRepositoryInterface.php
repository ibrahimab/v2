<?php
namespace AppBundle\Service\Api\File\Accommodation;
use		  AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;

interface AccommodationServiceRepositoryInterface
{
	public function getMainImage(AccommodationServiceEntityInterface $accommodation);
	public function getImages(AccommodationServiceEntityInterface $accommodation);
	public function getSearchImages($accommodations);
}