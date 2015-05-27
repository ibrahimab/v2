<?php
namespace AppBundle\Service\Api\File\Country;
use		  AppBundle\Service\Api\Country\CountryServiceEntityInterface;

class CountryService
{
	const COUNTRY_IMAGE = 'landen';

	private $placeServiceRepository;

	public function __construct(CountryServiceRepositoryInterface $countryServiceRepository)
	{
		$this->countryServiceRepository = $countryServiceRepository;
	}

	public function getImage($countryId)
	{
		return $this->countryServiceRepository->getImage($countryId);
	}
}