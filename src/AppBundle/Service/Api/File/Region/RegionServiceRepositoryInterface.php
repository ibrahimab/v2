<?php
namespace AppBundle\Service\Api\File\Region;
use		  AppBundle\Service\Api\Region\RegionServiceEntityInterface;

interface RegionServiceRepositoryInterface
{
	public function getImage(RegionServiceEntityInterface $region);
	public function getSkiRunsMapImage(RegionServiceEntityInterface $region);
}