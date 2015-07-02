<?php
namespace AppBundle\Service\Api\Theme;

/**
 * This is the ThemeService, with this service you can manipulate themes
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @version 0.0.5
 * @since   0.0.5
 * @package Chalet
 */
class ThemeService
{
    /**
     * @var ThemeServiceRepositoryInterface
     */
    private $themeServiceRepository;

    /**
     * Constructor
     *
     * @param ThemeServiceRepositoryInterface $faqServiceRepository
     */
    public function __construct(ThemeServiceRepositoryInterface $themeServiceRepository)
    {
        $this->themeServiceRepository = $themeServiceRepository;
    }

    /**
     * Get active themes
     *
     * @return ThemeServiceEntityInterface[]
     */
    public function themes()
    {
        return $this->themeServiceRepository->themes();
    }
}