<?php
namespace AppBundle\Twig;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\Theme\ThemeServiceEntityInterface;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       AppBundle\Service\Api\User\UserServiceDocumentInterface;
use       AppBundle\Service\FilterService;
use       AppBundle\Old\Service\PageService;
use       AppBundle\Service\Api\GeneralSettings\GeneralSettingsService;
use       AppBundle\Service\UtilsService;
use       Symfony\Component\DependencyInjection\ContainerInterface;
use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use       Symfony\Component\Finder\Finder;

/**
 * AppExtension
 *
 * Global application extensions for twig templates inside AppBundle
 *
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class AppExtension extends \Twig_Extension
{
    use Extension\Helper;
    use Extension\Image;
    use Extension\Url;
    use Extension\User;
    use Extension\Utils;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @var string
     */
    private $oldImageRoot;

    /**
     * @var UserServiceDocumentInterface
     */
    private $currentUser;

    /**
     * @var FilterService
     */
    private $filterService;

    /**
     * @var WebsiteConcern
     */
    private $websiteConcern;

    /**
     * @var GeneralSettingsService
     */
    private $generalSettingsService;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, UrlGeneratorInterface $generator)
    {
        $this->container      = $container;
        $this->generator      = $generator;
        $this->currentUser    = null;
        $this->locale         = $this->container->get('request')->getLocale();
        $this->filterService  = $this->container->get('app.filter');
        $this->websiteConcern = $this->container->get('app.concern.website');
        $this->fileServices   = [];
    }

    /**
     * Registering functions
     *
     * @return array
     */
    public function getFunctions()
    {
        return [

            new \Twig_SimpleFunction('locale_url', [$this, 'getUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new \Twig_SimpleFunction('locale_path', [$this, 'getPath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new \Twig_SimpleFunction('type_image', [$this, 'getTypeImage']),
            new \Twig_SimpleFunction('type_images', [$this, 'getTypeImages']),
            new \Twig_SimpleFunction('search_images', [$this, 'getSearchImages']),
            new \Twig_SimpleFunction('region_image', [$this, 'getRegionImage']),
            new \Twig_SimpleFunction('region_images', [$this, 'getRegionImages']),
            new \Twig_SimpleFunction('country_image', [$this, 'getCountryImage']),
            new \Twig_SimpleFunction('place_image', [$this, 'getPlaceImage']),
            new \Twig_SimpleFunction('place_images', [$this, 'getPlaceImages']),
            new \Twig_SimpleFunction('theme_image', [$this, 'getThemeImage']),
            new \Twig_SimpleFunction('homepage_block_image', [$this, 'getHomepageBlockImage']),
            new \Twig_SimpleFunction('generate_image_path', [$this, 'generateImagePath']),
            new \Twig_SimpleFunction('breadcrumbs', [$this, 'breadcrumbs'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('get_locale', [$this, 'getLocale']),
            new \Twig_SimpleFunction('js_object', [$this, 'getJavascriptObject']),
            new \Twig_SimpleFunction('region_skimap_image', [$this, 'getRegionSkimapImage']),
            new \Twig_SimpleFunction('favorites_count', [$this, 'favoritesCount']),
            new \Twig_SimpleFunction('viewed_count', [$this, 'viewedCount']),
            new \Twig_SimpleFunction('render_rate_table', [$this, 'renderRateTable']),
            new \Twig_SimpleFunction('searches_count', [$this, 'searchesCount']),
            new \Twig_SimpleFunction('is_checked', [$this, 'isChecked']),
            new \Twig_SimpleFunction('pdf_link', [$this, 'pdfLink']),
            new \Twig_SimpleFunction('website', [$this, 'website']),
            new \Twig_SimpleFunction('is_favorite', [$this, 'isFavorite']),
            new \Twig_SimpleFunction('opened', [$this, 'opened']),
            new \Twig_SimpleFunction('show_sunny_cars', [$this, 'showSunnyCars']),
        ];
    }

    /**
     * Registering filters
     *
     * @return array
     */
    public function getFilters()
    {
        return [

            new \Twig_SimpleFilter('bbcode', [$this, 'bbcode'], array('pre_escape' => 'html', 'is_safe' => array('html'))),
            new \Twig_SimpleFilter('sortprop', [$this, 'sortByProperty']),
            new \Twig_SimpleFilter('seo', [$this, 'seo']),
            new \Twig_SimpleFilter('array_replace', [$this, 'replace']),
            new \Twig_SimpleFilter('tokenize', [$this, 'tokenize']),
        ];
    }

    /**
     * @param TypeServiceEntityInterface $type
     * @return string
     */
    public function renderRateTable(TypeServiceEntityInterface $type)
    {
        $wrapper = $this->container->get('old.rate.table.wrapper');
        $wrapper->setType($type);

        return $wrapper->render();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_extension';
    }
}