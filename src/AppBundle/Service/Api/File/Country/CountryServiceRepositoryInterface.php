<?php
namespace AppBundle\Service\Api\File\Country;
use		  AppBundle\Service\Api\Country\CountryServiceEntityInterface;

interface CountryServiceRepositoryInterface
{
	public function getImage($countryId);
}