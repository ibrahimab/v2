<?php
namespace AppBundle\Service\Api\File\Type;
use		  AppBundle\Service\Api\File\FileServiceEntityInterface;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * TypeServiceRepositoryInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface TypeServiceRepositoryInterface
{
	/**
	 * Getting main image
	 *
	 * @param TypeServiceEntityInterface $type
	 * @return FileServiceEntityInterface
	 */
	public function getMainImage(TypeServiceEntityInterface $type);

	/**
	 * Getting all the images
	 *
	 * @param TypeServiceEntityInterface $type
	 * @return FileServiceEntityInterface
	 */
	public function getImages(TypeServiceEntityInterface $type);
}