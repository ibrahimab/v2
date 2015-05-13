<?php
namespace AppBundle\Twig;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       Symfony\Component\DependencyInjection\ContainerInterface;
use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use       Symfony\Component\Finder\Finder;
use       Symfony\Bridge\Twig\Extension\RoutingExtension;

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
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, UrlGeneratorInterface $generator)
    {
        $this->container = $container;
        $this->generator = $generator;
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
            new \Twig_SimpleFunction('region_image', [$this, 'getRegionImage']),
            new \Twig_SimpleFunction('place_image', [$this, 'getPlaceImage']),
            new \Twig_SimpleFunction('homepage_block_image', [$this, 'getHomepageBlockImage']),
            new \Twig_SimpleFunction('breadcrumbs', [$this, 'breadcrumbs'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('get_locale', [$this, 'getLocale']),
            new \Twig_SimpleFunction('js_object', [$this, 'getJavascriptObject']),
            new \Twig_SimpleFunction('region_skirun_map_image', [$this, 'getRegionSkiRunMapImage']),
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
        return '/chalet/pic/cms/';
    }

    /**
     * Getting Image url from a Type Entity
     *
     * @param TypeServiceEntityInterface $type
     * @return string
     */
    public function getTypeImage(TypeServiceEntityInterface $type)
    {
        $filename          = 'accommodaties/0.jpg';
        $typeId            = $type->getId();
        $accommodationId   = $type->getAccommodation()->getId();
        $typeFile          = $this->getOldImageRoot() . '/cms/hoofdfoto_type/' . $typeId . '.jpg';
        $accommodationFile = $this->getOldImageRoot() . '/cms/hoofdfoto_accommodatie/' . $accommodationId . '.jpg';

        if (file_exists($typeFile)) {

            $filename = 'hoofdfoto_type/' . $typeId. '.jpg';

        } elseif (file_exists($accommodationFile)) {

            $filename = 'hoofdfoto_accommodatie/' . $accommodationId . '.jpg';
        }

        return $this->getOldImageUrlPrefix() . $filename;
    }

    /**
     * Getting all the type images from its directory
     *
     * @return Finder
     */
    public function getTypeImages(TypeServiceEntityInterface $type, $above_limit = 3, $below_limit = 2)
    {
        $dir        = $this->getOldImageRoot() . '/cms/';
        $finder     = new Finder();
        $foundFiles = $finder->files()
                         ->in([
                             $dir . 'types',
                             $dir . 'types_specifiek',
                             $dir . 'types_specifiek_tn',
                             $dir . 'hoofdfoto_type',
                             $dir . 'types_breed',
                             $dir . 'hoofdfoto_accommodatie'
                         ])
                         ->depth('== 0')
                         ->name('/^' . $type->getId() . '(-[0-9]+)?\.jpg/i');

        $files = ['rest' => [], 'above' => [], 'below' => []];

        $i = 1;
        foreach ($foundFiles as $file) {

            $parent         = basename($file->getPath());
            $filename       = $this->getOldImageUrlPrefix() . $parent . '/' . $file->getFilename();

            if ($parent === 'types' && $i <= $above_limit) {
                $files['above'][] = $filename;
            }

            if ($i <= $below_limit) {

                $files['below'][] = $filename;

            } else {
                $files['rest'][] = $filename;
            }

            $i += 1;
        }

        return $files;
    }

    /**
     * Getting Region ski runs map image
     *
     * @param RegionServiceEntityInterface $region
     * @return string
     */
    public function getRegionImage(RegionServiceEntityInterface $region)
    {
        $finder   = new Finder();
        $iterator = $finder->files()
                           ->in($this->getOldImageRoot() . '/cms/skigebieden')
                           ->name('/^' . $region->getId() . '-1\.jpg$/i')
                           ->getIterator();
        $iterator->next();
        $file = $iterator->current();

        return $this->getOldImageUrlPrefix() . 'skigebieden/' . ($file === null ? '0.jpg' : $file->getFilename());
    }

    /**
     * Getting Region ski runs map image
     *
     * @param RegionServiceEntityInterface $region
     * @return string
     */
    public function getRegionSkiRunMapImage(RegionServiceEntityInterface $region)
    {
        $file     = $this->getOldImageRoot() . '/cms/skigebieden_pistekaarten/' . $region->getId() . '.jpg';
        $filename = 'skigebieden_pistekaarten/0.jpg';

        if (file_exists($file)) {
            $filename = 'skigebieden_pistekaarten/' . $region->getId(). '.jpg';
        }

        return $this->getOldImageUrlPrefix() . $filename;
    }

    /**
     * Getting Place image
     *
     * @param PlaceServiceEntityInterface $place
     * @return string
     */
    public function getPlaceImage(PlaceServiceEntityInterface $place)
    {
        $finder   = new Finder();
        $iterator = $finder->files()
                           ->in($this->getOldImageRoot() . '/cms/plaatsen')
                           ->name('/^' . $place->getId() . '-1\.jpg$/i')
                           ->getIterator();
        $iterator->next();
        $file = $iterator->current();

        return $this->getOldImageUrlPrefix() . 'plaatsen/' . ($file === null ? '0.jpg' : $file->getFilename());
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

        return $this->getOldImageUrlPrefix() . $filename;
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
        $locale = $this->container->get('request')->getLocale();
        $exists = $this->generator->getRouteCollection()->get($name . '_' . $locale) !== null;

        return $this->generator->generate(($name . ($exists ? ('_' . $locale) : '')), $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
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
        $locale = $this->container->get('request')->getLocale();
        $exists = $this->generator->getRouteCollection()->get($name . '_' . $locale) !== null;

        return $this->generator->generate(($name . ($exists ? ('_' . $locale) : '')), $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
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
        return $this->container->get('service.javascript')->toArray();
    }

    /**
     * Helper method for formatting bbcode
     *
     * @param string $text
     * @return string
     */
    public function bbcode($text)
    {
        return $this->container->get('service.utils')->bbcode($text);
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
		return $this->container->get('service.utils')->seo($text);
	}

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_extension';
    }
}