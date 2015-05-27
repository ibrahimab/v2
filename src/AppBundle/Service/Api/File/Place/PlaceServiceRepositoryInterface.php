<?php
namespace AppBundle\Service\Api\File\Place;
use		  AppBundle\Service\Api\Place\PlaceServiceEntityInterface;

interface PlaceServiceRepositoryInterface
{
	public function getImage(PlaceServiceEntityInterface $place);
}