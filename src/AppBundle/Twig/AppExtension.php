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
use       AppBundle\Service\Api\Search\Filter\Tokenizer;
use       AppBundle\Service\Api\GeneralSettings\GeneralSettingsService;
use       AppBundle\Service\UtilsService;
use       AppBundle\Service\Javascript\JavascriptService;
use       AppBundle\Concern\LocaleConcern;
use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use       Symfony\Component\Finder\Finder;

/**
 * AppExtension
 *
 * Global application extensions for twig templates inside AppBundle
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 * @version 0.0.3
 * @since   0.0.3
 */
class AppExtension extends \Twig_Extension
{
    use Extension\Dependencies;
    use Extension\Helper;
    use Extension\Image;
    use Extension\Url;
    use Extension\User;
    use Extension\Utils;
    use Extension\LegacyCmsUser;

    /**
     * @var PriceTable
     */
    private $priceTable;

    /**
     * @var LocaleConcern
     */
    private $localeConcern;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @var string
     */
    private $oldImageRoot;

    /**
     * @var string
     */
    private $oldSitePrefix;

    /**
     * @var UserServiceDocumentInterface
     */
    private $currentUser;

    /**
     * @var Tokenizer
     */
    private $filterTokenizer;

    /**
     * @var WebsiteConcern
     */
    private $websiteConcern;

    /**
     * @var GeneralSettingsService
     */
    private $generalSettingsService;

    /**
     * @var CmsUserService
     */
    private $cmsUserService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->currentUser       = null;
        $this->fileServices      = [];
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
            new \Twig_SimpleFunction('searches_count', [$this, 'searchesCount']),
            new \Twig_SimpleFunction('is_checked', [$this, 'isChecked']),
            new \Twig_SimpleFunction('pdf_link', [$this, 'pdfLink']),
            new \Twig_SimpleFunction('website', [$this, 'website']),
            new \Twig_SimpleFunction('is_favorite', [$this, 'isFavorite']),
            new \Twig_SimpleFunction('opened', [$this, 'opened']),
            new \Twig_SimpleFunction('show_sunny_cars', [$this, 'showSunnyCars']),
            new \Twig_SimpleFunction('should_show_internal_info', [$this, 'shouldShowInternalInfo']),
            new \Twig_SimpleFunction('get_cms_info', [$this, 'getCmsInfo']),
            new \Twig_SimpleFunction('asset_prevent_cache', [$this, 'assetPreventCache']),

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
            new \Twig_SimpleFilter('thumbnail', [$this, 'generateThumbnailPath']),
            new \Twig_SimpleFilter('format_date', [$this, 'formatDate']),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_extension';
    }
}
