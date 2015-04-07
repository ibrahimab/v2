<?php
namespace AppBundle\Twig;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceEntityInterface;
use       Symfony\Component\DependencyInjection\ContainerInterface;
use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
            new \Twig_SimpleFunction('region_image', [$this, 'getRegionImage']),
            new \Twig_SimpleFunction('place_image', [$this, 'getPlaceImage']),
            new \Twig_SimpleFunction('homepage_block_image', [$this, 'getHomepageBlockImage']),
            new \Twig_SimpleFunction('breadcrumbs', [$this, 'breadcrumbs'], ['is_safe' => ['html'], 'needs_environment' => true]),
            new \Twig_SimpleFunction('get_locale', [$this, 'getLocale']),
            new \Twig_SimpleFunction('js_object', [$this, 'getJavascriptObject']),
            new \Twig_SimpleFunction('region_ski_run_map', [$this, 'getRegionSkiRunMap']),
        ];
    }
    
    /**
     * Getting Image url from a Type Entity
     *
     * @param TypeServiceEntityInterface $type
     * @return string
     */
    public function getTypeImage(TypeServiceEntityInterface $type)
    {
        $rootDir = $this->container->get('kernel')->getRootDir();
        $path    = dirname($rootDir) . '/web/chalet/pic/cms/';
        $file    = 'accommodaties/0';
        $cache   = 'pic/cms/';

        if (file_exists($path . 'hoofdfoto_type/' . $type->getId() . '.jpg')) {
            $file = 'hoofdfoto_type/' . $type->getId();
        } elseif (file_exists($path . 'hoofdfoto_accommodatie/' . $type->getAccommodation()->getId() . '.jpg')) {
            $file = 'hoofdfoto_accommodatie/' . $type->getAccommodation()->getId();
        }
        
        return '/chalet/' . $cache . $file . '.jpg';
    }
    
    public function getRegionImage(RegionServiceEntityInterface $region)
    {
        $rootDir   = $this->container->get('kernel')->getRootDir();
        $path      = dirname($rootDir) . '/web/chalet/pic/cms/skigebieden/';
        $directory = new \DirectoryIterator($path);
        $iterator  = new \IteratorIterator($directory);
        $pattern   = new \RegexIterator($iterator, '/^' . $region->getId() . '-[0-9]{1,3}\.jpg$/i', \RecursiveRegexIterator::GET_MATCH);
        
        // get first image
        $pattern->next();
        
        $filename = $pattern->current();
        if (is_array($filename)) {
            $filename = 'skigebieden/' . $filename[0];
        } else {
            $filename = 'accommodaties/0.jpg';
        }
        
        return '/chalet/pic/cms/' . $filename;
    }
    
    public function getRegionSkiRunMap(RegionServiceEntityInterface $region)
    {
        $rootDir = $this->container->get('kernel')->getRootDir();
        $path    = dirname($rootDir) . '/web/chalet/pic/cms/skigebieden_pistekaarten/';

        if (file_exists($path . $region->getId() . '.jpg')) {
            return '/chalet/pic/cms/skigebieden_pistekaarten/' . $region->getId(). '.jpg';
        }
        
        return null;
    }
    
    public function getPlaceImage(PlaceServiceEntityInterface $place)
    {
        $rootDir   = $this->container->get('kernel')->getRootDir();
        $path      = dirname($rootDir) . '/web/chalet/pic/cms/plaatsen/';
        $directory = new \DirectoryIterator($path);
        $iterator  = new \IteratorIterator($directory);
        $pattern   = new \RegexIterator($iterator, '/^' . $place->getId() . '-[0-9]{1,3}\.jpg$/i', \RecursiveRegexIterator::GET_MATCH);
        
        // get first image
        $pattern->next();
        
        $filename = $pattern->current();
        if (is_array($filename)) {
            $filename = 'plaatsen/' . $filename[0];
        } else {
            $filename = 'plaatsen/0.jpg';
        }
        
        return '/chalet/pic/cms/' . $filename;
    }
    
    public function getHomepageBlockImage(HomepageBlockServiceEntityInterface $homepageBlock)
    {
        $rootDir = $this->container->get('kernel')->getRootDir();
        $path    = dirname($rootDir) . '/web/chalet/pic/cms/homepageblokken/';

        if (file_exists($path . $homepageBlock->getId() . '.jpg')) {
            return '/chalet/pic/cms/homepageblokken/' . $homepageBlock->getId(). '.jpg';
        }
        
        return null;
    }
    
    /**
     * Wrapper around path function of twig to automatically add _<locale> to the route name
     */
    public function getPath($name, $parameters = array(), $relative = false)
    {
        $locale = $this->container->get('request')->getLocale();
        $exists = $this->generator->getRouteCollection()->get($name . '_' . $locale) !== null;
        
        return $this->generator->generate(($name . ($exists ? ('_' . $locale) : '')), $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
    
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
    
    public function breadcrumbs(\Twig_Environment $twig, $placeholders = [], $params = [])
    {
        $annotations  = $this->container->get('request')->attributes->get('breadcrumbs');
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
    
    public function getLocale()
    {
        if (null === $this->locale) {
            $this->locale = $this->container->get('request')->getLocale();
        }
        
        return $this->locale;
    }
    
    public function getJavascriptObject()
    {
        return $this->container->get('service.javascript')->toArray();
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'app_bundle_extension';
    }
}