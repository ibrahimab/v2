<?php
namespace AppBundle\Service\Api\File\Type;
use		  AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use		  AppBundle\Service\Api\File\FileServiceEntityInterface;

/**
 * TypeService
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class TypeService
{
	const MAIN_IMAGE   = 'hoofdfoto_type';
	const SEARCH_IMAGE = 'hoofdfoto_type';

	/**
	 * @var TypeServiceRepositoryInterface
	 */
	private $typeServiceRepository;

	/**
	 * Constructor
	 *
	 * @param TypeServiceRepositoryInterface $typeServiceRepository
	 */
	public function __construct(TypeServiceRepositoryInterface $typeServiceRepository)
	{
		$this->typeServiceRepository = $typeServiceRepository;
	}

	/**
	 * Getting main image
	 *
	 * @param TypeServiceEntityInterface $type
	 * @return FileServiceEntityInterface
	 */
	public function getMainImage($type)
	{
		return $this->typeServiceRepository->getMainImage($type);
	}

	/**
	 * Getting all the images
	 *
	 * @param TypeServiceEntityInterface $type
	 * @return FileServiceEntityInterface
	 */
	public function getImages($type)
	{
		return $this->typeServiceRepository->getImages($type);
	}

	/**
	 * Getting all the images
	 *
	 * @param TypeServiceEntityInterface[] $types
	 * @return FileServiceEntityInterface[]
	 */
	public function getSearchImages($types)
	{
		return $this->typeServiceRepository->getSearchImages($types);
	}
}