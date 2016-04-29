<?php
namespace AppBundle\Twig\Extension;

use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author  Ibrahim Abdullah
 * @package Chalet
 */
trait Url
{
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
        $locale = $this->localeConcern->get();
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
        $locale = $this->localeConcern->get();
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
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return '';
        }

        $annotations  = $request->attributes->get('_breadcrumbs');
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
     * Prevent caching of assets by adding
     *
     * @param string $file
     * @return string
     */
    public function assetPreventCache($file)
    {

        return $file . '?' . filemtime($file);

    }

}