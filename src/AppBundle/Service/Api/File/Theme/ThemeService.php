<?php
namespace AppBundle\Service\Api\File\Theme;
use		  AppBundle\Service\Api\Theme\ThemeServiceEntityInterface;
use       AppBundle\Document\File\Theme as ThemeFileDocument;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class ThemeService
{
    /**
     * @const string
     */
	const THEME_IMAGE = 'themas_hoofdpagina';

    /**
     * @var ThemeServiceRepositoryInterface
     */
	private $themeServiceRepository;

    /**
     * Constructor
     *
     * @param ThemeServiceRepositoryInterface $themeServiceRepository
     */
	public function __construct(ThemeServiceRepositoryInterface $themeServiceRepository)
	{
		$this->themeServiceRepository = $themeServiceRepository;
	}

    /**
     * @param ThemeServiceEntityInterface $theme
     * @return ThemeFileDocument
     */
	public function getImage(ThemeServiceEntityInterface $theme)
	{
		return $this->themeServiceRepository->getImage($theme);
	}
}