<?php
namespace AppBundle\Document\File;
use		  AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\File\Region\RegionService;
use		  AppBundle\Service\Api\File\Region\RegionServiceRepositoryInterface;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * AccommodationRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class RegionRepository extends DocumentRepository implements RegionServiceRepositoryInterface
{
	public function getImage(RegionServiceEntityInterface $region)
	{
		return $this->findOneBy(['file_id' => $region->getId(), 'kind' => RegionService::REGION_IMAGE, 'rank' => 1]);
	}

	public function getSkiRunsMapImage(RegionServiceEntityInterface $region)
	{
		return $this->findOneBy(['file_id' => $region->getId(), 'kind' => RegionService::SKI_RUNS_MAP_IMAGE]);
	}
}