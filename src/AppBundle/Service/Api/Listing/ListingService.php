<?php
namespace AppBundle\Service\Api\Listing;
use 	  AppBundle\Service\Api\Type\TypeServiceEntityInterface;

class ListingService
{
	/**
	 * @var ListingServiceRepositoryInterface
	 */
	private $listingServiceRepository;

	public function __construct(ListingServiceRepositoryInterface $listingServiceRepository)
	{
		$this->listingServiceRepository = $listingServiceRepository;
	}

	public function favorites($userId, $options = [])
	{
		return $this->listingServiceRepository->favorites($userId, $options);
	}

	public function favorite($userId, TypeServiceEntityInterface $type)
	{
		return $this->listingServiceRepository->favorite($userId, $type);
	}
}