<?php
namespace AppBundle\Service\Api\File\Theme;
use		  AppBundle\Service\Api\Theme\ThemeServiceEntityInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface ThemeServiceRepositoryInterface
{
	public function getImage(ThemeServiceEntityInterface $theme);
}