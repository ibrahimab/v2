<?php
namespace AppBundle\Document\File;
use		  AppBundle\Service\Api\Theme\ThemeServiceEntityInterface;
use       AppBundle\Service\Api\File\Theme\ThemeService;
use		  AppBundle\Service\Api\File\Theme\ThemeServiceRepositoryInterface;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * ThemeRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class ThemeRepository extends DocumentRepository implements ThemeServiceRepositoryInterface
{
	public function getImage(ThemeServiceEntityInterface $theme)
	{
		return $this->findOneBy(['file_id' => $theme->getId(), 'kind' => ThemeService::THEME_IMAGE, 'rank' => 1]);
	}
}