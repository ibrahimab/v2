<?php
namespace AppBundle\Service\Api\File\Accommodation;
use		  AppBundle\Service\Api\Accommodation\AccommodationServiceEntityInterface;

interface AccommodationServiceRepositoryInterface
{
	public function getMainImage($accommodation);
	public function getImages($accommodation);
	public function getSearchImages($accommodations);
}