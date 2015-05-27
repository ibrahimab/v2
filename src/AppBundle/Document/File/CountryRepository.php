<?php
namespace AppBundle\Document\File;
use		  AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\File\Country\CountryService;
use		  AppBundle\Service\Api\File\Country\CountryServiceRepositoryInterface;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * CountryRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class CountryRepository extends DocumentRepository implements CountryServiceRepositoryInterface
{
	public function getImage($countryId)
	{
		return $this->findOneBy(['file_id' => $countryId, 'kind' => CountryService::COUNTRY_IMAGE], ['$orderby' => ['rank' => 1]]);
	}
}