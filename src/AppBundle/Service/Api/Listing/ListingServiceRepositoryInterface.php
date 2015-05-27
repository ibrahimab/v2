<?php
namespace AppBundle\Service\Api\Listing;
use 	  AppBundle\Service\Api\Type\TypeServiceEntityInterface;

interface ListingServiceRepositoryInterface
{
	public function favorites($userId, $options = []);
	public function favorite($userId, TypeServiceEntityInterface $type);
}