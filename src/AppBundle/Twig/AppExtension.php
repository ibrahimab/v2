<?php
namespace AppBundle\Twig;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\Theme\ThemeServiceEntityInterface;
use       AppBundle\Document\File\Country\CountryFileDocument;
use       AppBundle\Service\Api\File\Type\TypeService as TypeFileService;
use       AppBundle\Document\File\Type as TypeFileDocument;
use       AppBundle\Service\Api\File\Accommodation\AccommodationService as AccommodationFileService;
use       AppBundle\Document\File\Accommodation as AccommodationFileDocument;
use       AppBundle\Service\Api\File\Region\RegionService as RegionFileService;
use       AppBundle\Document\File\Region as RegionFileDocument;
use       AppBundle\Document\File\Place as PlaceFileDocument;
use       AppBundle\Service\Api\File\Theme\ThemeService as ThemeFileService;
use       AppBundle\Document\File\Theme as ThemeFileDocument;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       AppBundle\Service\Api\User\UserServiceDocumentInterface;
use       AppBundle\Service\FilterService;
use       AppBundle\Old\Service\PageService;
use       AppBundle\Service\Api\GeneralSettings\GeneralSettingsService;
use       Symfony\Component\DependencyInjection\ContainerInterface;
use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use       Symfony\Component\Finder\Finder;
use       Symfony\Bridge\Twig\Extension\RoutingExtension;

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
            new \Twig_SimpleFunction('country_image', [$this, 'getCountryImage']),
            new \Twig_SimpleFunction('place_image', [$this, 'getPlaceImage']),
            new \Twig_SimpleFunction('theme_image', [$this, 'getThemeImage']),
            new \Twig_SimpleFunction('homepage_block_image', [$this, 'getHomepageBlockImage']),
            new \Twig_SimpleFunction('breadcrumbs', [$this, 'breadcrumbs'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('get_locale', [$this, 'getLocale']),
            new \Twig_SimpleFunction('js_object', [$this, 'getJavascriptObject']),
            new \Twig_SimpleFunction('region_skirun_map_image', [$this, 'getRegionSkiRunMapImage']),
            new \Twig_SimpleFunction('favorites_count', [$this, 'favoritesCount']),
            new \Twig_SimpleFunction('viewed_count', [$this, 'viewedCount']),
            new \Twig_SimpleFunction('render_rate_table', [$this, 'renderRateTable']),
            new \Twig_SimpleFunction('searches_count', [$this, 'searchesCount']),
            new \Twig_SimpleFunction('is_checked', [$this, 'isChecked']),
            new \Twig_SimpleFunction('pdf_link', [$this, 'pdfLink']),
            new \Twig_SimpleFunction('website', [$this, 'website']),
            new \Twig_SimpleFunction('is_favorite', [$this, 'isFavorite']),
            new \Twig_SimpleFunction('opened', [$this, 'opened']),
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
     * Returns old image root directory
     *
     * @return string
     */
    public function getOldImageRoot()
    {
        if (null === $this->oldImageRoot) {
            $this->oldImageRoot = dirname($this->container->get('kernel')->getRootDir()) . '/web/chalet/pic';
        }

        return $this->oldImageRoot;
    }

    /**
     * Returns old image url prefix
     */
    public function getOldImageUrlPrefix()
    {
        return '/chalet-pic';
    }

    /**
     * Getting Image url from a Type Entity
     *
     * @param TypeServiceEntityInterface $type
     * @return string
     */
    public function getTypeImage(TypeServiceEntityInterface $type)
    {
        $typeFileService = $this->container->get('app.api.file.type');
        $mainImage       = $typeFileService->getMainImage($type);

        if (null === $mainImage) {

            $accommodationFileService = $this->container->get('app.api.file.accommodation');
            $mainImage                = $accommodationFileService->getMainImage($type->getAccommodation());

            if (null === $mainImage) {

                $mainImage = new AccommodationFileDocument();
                $mainImage->setDirectory('accommodaties');
                $mainImage->setFilename('0.jpg');
            }
        }

        $mainImage->setUrlPrefix($this->getOldImageUrlPrefix());

        return $mainImage;
    }

    /**
     * Getting all the type images from its directory
     *
     * @param TypeServiceEntityInterface $type
     * @param int $above_limit This defines how many images above should be displayed
     * @param int $below_limit This defines how many image below are displayed by default
     * @return []
     */
    public function getTypeImages(TypeServiceEntityInterface $type, $above_limit = 3, $below_limit = 2)
    {
        $typeFileService = $this->container->get('app.api.file.type');
        $files           = $typeFileService->getImages($type);
        $images          = ['above' => [], 'below' => [], 'rest' => []];
        $above_done      = 1;
        $below_done      = 1;

        foreach ($files as $file) {

            $file->setUrlPrefix($this->getOldImageUrlPrefix());

            if ($file->getKind() === TypeFileService::MAIN_IMAGE && $above_done <= $above_limit) {

                $images['above'][] = $file;
                $above_done       += 1;

            } else {

                $images[($below_done <= $below_limit ? 'below': 'rest')][] = $file;

                $below_done += 1;
            }
        }

        if (count($images['above']) === 0) {

            $accommodationFileService = $this->container->get('app.api.file.accommodation');
            $files                    = $accommodationFileService->getImages($type->getAccommodation());

            foreach ($files as $file) {

                $file->setUrlPrefix($this->getOldImageUrlPrefix());

                if ($file->getKind() === AccommodationFileService::MAIN_IMAGE && $above_done <= $above_limit) {

                    $images['above'][] = $file;
                    $above_done       += 1;

                } else {

                    $images[($below_done <= $below_limit ? 'below': 'rest')][] = $file;

                    $below_done += 1;
                }
            }
        }

        return $images;
    }

    /**
     * @param AccommodationServiceEntityInterface[] $accommodations
     * @return []
     */
    public function getSearchImages($accommodations)
    {
        $types = [];
        $accommodationEntities = [];

        foreach ($accommodations as $accommodation) {

            $accommodationTypes = $accommodation->getTypes();
            if (count($accommodationTypes) > 1) {

                $types[] = $accommodationTypes[0];
                $accommodationEntities[$accommodationTypes[0]->getId()] = $accommodation;
            }
        }

        $typeFileService    = $this->container->get('app.api.file.type');
        $files              = $typeFileService->getSearchImages($types);
        $images             = [];
        $found              = [];
        $mapper             = [];
        $accommodationFiles = [];

        foreach ($files as $file) {
            $found[$file->getFileId()] = true;
        }

        $notFound = [];
        foreach ($accommodationEntities as $typeId => $accommodation) {

            if (!array_key_exists($typeId, $found)) {

                $notFound[] = $accommodation;
                $mapper[$accommodation->getId()] = $typeId;
            }
        }

        if (count($notFound) > 0) {

            $accommodationFileService = $this->container->get('app.api.file.accommodation');
            $accommodationFiles       = $accommodationFileService->getSearchImages($notFound);
        }

        foreach ($files as $file) {

            if (!isset($images[$file->getFileId()])) {
                $images[$file->getFileId()] = [];
            }

            $file->setUrlPrefix($this->getOldImageUrlPrefix());
            $images[$file->getFileId()][] = $file;
        }

        foreach ($accommodationFiles as $file) {

            if (!isset($mapper[$file->getFileId()])) {
                continue;
            }

            $typeId = $mapper[$file->getFileId()];

            if (!isset($images[$typeId])) {
                $images[$typeId] = [];
            }

            $file->setUrlPrefix($this->getOldImageUrlPrefix());
            $images[$typeId][] = $file;
        }

        return $images;
    }

    /**
     * Getting Region ski runs map image
     *
     * @param RegionServiceEntityInterface $region
     * @return string
     */
    public function getRegionImage(RegionServiceEntityInterface $region)
    {
        $regionFileService = $this->container->get('app.api.file.region');
        $regionImage       = $regionFileService->getImage($region);

        if (null === $regionImage) {

            $regionImage = new RegionFileDocument();
            $regionImage->setDirectory('accommodaties');
            $regionImage->setFilename('0.jpg');
        }

        $regionImage->setUrlPrefix($this->getOldImageUrlPrefix());

        return $regionImage;
    }

    /**
     * Getting Region ski runs map image
     *
     * @param RegionServiceEntityInterface $region
     * @return string
     */
    public function getRegionSkiRunMapImage(RegionServiceEntityInterface $region)
    {
        $regionFileService = $this->container->get('app.api.file.region');
        $regionImage       = $regionFileService->getSkiRunsMapImage($region);

        if (null === $regionImage) {

            $regionImage = new RegionFileDocument();
            $regionImage->setUrlPrefix($this->getOldImageUrlPrefix());
            $regionImage->setDirectory('accommodaties');
            $regionImage->setFilename('0.jpg');
        }

        return $regionImage;
    }

    /**
     * Getting Place image
     *
     * @param PlaceServiceEntityInterface $place
     * @return string
     */
    public function getPlaceImage(PlaceServiceEntityInterface $place)
    {
        $placeFileService = $this->container->get('app.api.file.place');
        $placeImage       = $placeFileService->getImage($place);

        if (null === $placeImage) {

            $placeImage = new PlaceFileDocument();
            $placeImage->setDirectory('accommodaties');
            $placeImage->setFilename('0.jpg');
        }

        $placeImage->setUrlPrefix($this->getOldImageUrlPrefix());

        return $placeImage;
    }

    /**
     * Getting Theme image
     *
     * @param ThemeServiceEntityInterface $theme
     * @return string
     */
    public function getThemeImage(ThemeServiceEntityInterface $theme)
    {
        $themeFileService = $this->container->get('app.api.file.theme');
        $themeImage       = $themeFileService->getImage($theme);

        if (null === $themeImage) {

            $themeImage = new ThemeFileDocument();
            $themeImage->setDirectory('accommodaties');
            $themeImage->setFilename('0.jpg');
        }

        $themeImage->setUrlPrefix($this->getOldImageUrlPrefix());

        return $themeImage;
    }

    /**
     * Getting Homepage block image
     *
     * @param HomepageBlockServiceEntityInterface $homepageBlock
     * @return string|null
     */
    public function getHomepageBlockImage(HomepageBlockServiceEntityInterface $homepageBlock)
    {
        $homepageBlockId = $homepageBlock->getId();
        $file            = $this->getOldImageRoot() . '/cms/homepageblokken/' . $homepageBlockId . '.jpg';
        $filename        = 'homepageblokken/0.jpg';

        if (file_exists($file)) {
            $filename = 'homepageblokken/' . $homepageBlockId . '.jpg';
        }

        return $this->getOldImageUrlPrefix() . '/' . $filename;
    }

    public function getCountryImage($countryId)
    {
        $countryFileService = $this->container->get('app.api.file.country');
        $countryImage       = $countryFileService->getImage($countryId);

        if (null === $countryImage) {

            $countryImage = new CountryFileDocument();
            $countryImage->setUrlPrefix($this->getOldImageUrlPrefix());
            $countryImage->setDirectory('accommodaties');
            $countryImage->setFilename('0.jpg');
        }

        $countryImage->setUrlPrefix($this->getOldImageUrlPrefix());

        return $countryImage;
    }

    /**
     * Wrapper around path function of twig to automatically add _<locale> to the route name
     *
     * @param string $name
     * @param array $parameters
     * @param boolean $relative
     * @return string
     */
    public function getPath($name, $parameters = array(), $relative = false)
    {
        $exists = $this->generator->getRouteCollection()->get($name . '_' . $this->locale) !== null;

        return $this->generator->generate(($name . ($exists ? ('_' . $this->locale) : '')), $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * Getting absolute version of path (locale aware)
     *
     * @param string $name
     * @param array $parameters
     * @param boolean $schemeRelative
     * @return string
     */
    public function getUrl($name, $parameters = array(), $schemeRelative = false)
    {
        $exists = $this->generator->getRouteCollection()->get($name . '_' . $this->locale) !== null;

        return $this->generator->generate(($name . ($exists ? ('_' . $this->locale) : '')), $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * Determines at compile time whether the generated URL will be safe and thus
     * saving the unneeded automatic escaping for performance reasons.
     *
     * The URL generation process percent encodes non-alphanumeric characters. So there is no risk
     * that malicious/invalid characters are part of the URL. The only character within an URL that
     * must be escaped in html is the ampersand ("&") which separates query params. So we cannot mark
     * the URL generation as always safe, but only when we are sure there won't be multiple query
     * params. This is the case when there are none or only one constant parameter given.
     * E.g. we know beforehand this will be safe:
     * - path('route')
     * - path('route', {'param': 'value'})
     * But the following may not:
     * - path('route', var)
     * - path('route', {'param': ['val1', 'val2'] }) // a sub-array
     * - path('route', {'param1': 'value1', 'param2': 'value2'})
     * If param1 and param2 reference placeholder in the route, it would still be safe. But we don't know.
     *
     * @param \Twig_Node $argsNode The arguments of the path/url function
     *
     * @return array An array with the contexts the URL is safe
     */
    public function isUrlGenerationSafe(\Twig_Node $argsNode)
    {
        // support named arguments
        $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : (
            $argsNode->hasNode(1) ? $argsNode->getNode(1) : null
        );

        if (null === $paramsNode || $paramsNode instanceof \Twig_Node_Expression_Array && count($paramsNode) <= 2 &&
            (!$paramsNode->hasNode(1) || $paramsNode->getNode(1) instanceof \Twig_Node_Expression_Constant)
        ) {
            return array('html');
        }

        return array();
    }

    /**
     * Generating breadcrumbs
     *
     * @param \Twig_Environment $twig
     * @param array $placeholders
     * @param array $params
     * @return string
     */
    public function breadcrumbs(\Twig_Environment $twig, $placeholders = [], $params = [])
    {
        $annotations  = $this->container->get('request')->attributes->get('_breadcrumbs');
        $breadcrumbs  = [];
        $replacements = array_values($placeholders);
        $placeholders = array_map(function($placeholder) { return '{' . $placeholder .'}'; }, array_keys($placeholders));

        foreach ($annotations as $annotation) {

            $pathParams = [];
            foreach ($params as $key => $value) {

                if (in_array($key, $annotation->getPathParams())) {
                    $pathParams[$key] = $value;
                }
            }

            $breadcrumbs[] = [

               'title'     => str_replace($placeholders, $replacements, $annotation->getTitle()),
               'path'      => $annotation->getPath(),
               'params'    => $pathParams,
               'active'    => $annotation->getActive(),
               'translate' => $annotation->translate(),
            ];
        }

        return $twig->render('partials/breadcrumbs.html.twig', ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Getting locale
     *
     * @return string
     */
    public function getLocale()
    {
        if (null === $this->locale) {
            $this->locale = $this->container->get('request')->getLocale();
        }

        return $this->locale;
    }

    /**
     * Return Javascript Object created by controller/services
     *
     * @return array
     */
    public function getJavascriptObject()
    {
        return $this->container->get('app.javascript')->toArray();
    }

    /**
     * Helper method for formatting bbcode
     *
     * @param string $text
     * @return string
     */
    public function bbcode($text)
    {
        return $this->container->get('app.utils')->bbcode($text);
    }

    public function sortByProperty($objects, $property)
    {
        return usort($objects, function($a, $b) use ($property) {
            return strcmp($a->{'get' . $property}(), $b->{'get' . $property}());
        });
    }

    /**
     * Normalizing text
     *
     * @param string $text
     * @return string
     */
    public function seo($text)
    {
        return $this->container->get('app.utils')->seo($text);
    }

    /**
     * Count favorites
     *
     * @return int
     */
    public function favoritesCount()
    {
        if (null !== ($user = $this->getUser())) {
            return $user->totalFavorites();
        }

        return 0;
    }

    public function isFavorite(TypeServiceEntityInterface $type)
    {
        if (null !== ($user = $this->getUser())) {
            return in_array($type->getId(), $user->getFavorites());
        }

        return false;
    }

    /**
     * Count viewed
     *
     * @return int
     */
    public function viewedCount()
    {
        if (null !== ($user = $this->getUser())) {
            return $user->totalViewed();
        }

        return 0;
    }

    /**
     * Count searches
     *
     * @return int
     */
    public function searchesCount()
    {
        if (null !== ($user = $this->getUser())) {
            return $user->totalSearches();
        }

        return 0;
    }

    /**
     * @return AnonymousToken
     */
    protected function getUser()
    {
        if (null === $this->currentUser) {
            $this->currentUser = $this->container->get('app.api.user')->user();
        }

        return $this->currentUser;
    }

    /**
     * @param array $data
     * @param array $replacement
     * @param boolean $recursive
     * @return array
     */
    public function replace($data, $replacement, $recursive = false)
    {
        return (true === $recursive ? array_replace_recursive($data, $replacement) : array_replace($data, $replacement));
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
     * @param mixed $$data
     * @param mixed $item
     * @return string
     */
    public function isChecked($data, $item)
    {
        return ((null !== $data ? (is_array($data) ? in_array($item, $data) : (int)$data === (int)$item) : false) ? ' checked="checked"' : '');
    }

    /**
     * @param int $value
     * @param int $filter
     * @return string
     */
    public function tokenize($value, $filter = null)
    {
        // if second parameter is null, that means we want to tokenize a filter
        // otherwise tokenize the value
        return (null === $filter ? $this->filterService->tokenize($value) : $this->filterService->tokenize($filter, $value));
    }

    /**
     * @param string $link
     * @return string
     */
    public function pdfLink($link)
    {
        return '/chalet/' . $link;
    }

    /**
     * @return WebsiteConcern
     */
    public function website()
    {
        return $this->websiteConcern;
    }
    
    /**
     * @return boolean
     */
    public function opened()
    {
        if (null === $this->generalSettingsService) {
            $this->generalSettingsService = $this->container->get('app.api.general.settings');
        }
        
        return $this->generalSettingsService->opened();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_extension';
    }
}