<?php
namespace AppBundle\Service\Api\File\Region;
use		  AppBundle\Service\Api\Region\RegionServiceEntityInterface;

class RegionService
{
	const REGION_IMAGE       = 'skigebieden';
	const SKI_RUNS_MAP_IMAGE = 'skigebieden_pistekaarten';

	private $regionServiceRepository;

	public function __construct(RegionServiceRepositoryInterface $regionServiceRepository)
	{
		$this->regionServiceRepository = $regionServiceRepository;
	}

	public function getImage(RegionServiceEntityInterface $region)
	{
		return $this->regionServiceRepository->getImage($region);
	}

	public function getSkiRunsMapImage(RegionServiceEntityInterface $region)
	{
		return $this->regionServiceRepository->getSkiRunsMapImage($region);
	}
}