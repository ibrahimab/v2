<?php
namespace AppBundle\Twig\Extension;

use AppBundle\Service\UtilsService;
use AppBundle\Service\Api\Search\Filter\Filter;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
trait Utils
{
    /**
     * Getting locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->localeConcern->get();
    }

    /**
     * Return Javascript Object created by controller/services
     *
     * @return array
     */
    public function getJavascriptObject()
    {
        return $this->javascriptService->toArray();
    }

    /**
     * Helper method for formatting bbcode
     *
     * @param string $text
     * @return string
     */
    public function bbcode($text)
    {
        return UtilsService::bbcode($text);
    }

    /**
     * Helper method for formatting text with links
     *
     * @param string $text
     * @return string
     */
    public function linkify($text)
    {
        return UtilsService::linkify($text);
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
        return UtilsService::seo($text);
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
    public function tokenize($value, $filterId = null)
    {
        // if second parameter is null, that means we want to tokenize a filter
        // otherwise tokenize the value
        if (null === $filterId) {
            $filter = new Filter($value, null);
        } else {
            $filter = new Filter($filterId, $value);
        }

        return (null === $filterId ? $this->filterTokenizer->tokenize($filter) : $this->filterTokenizer->tokenize($filter, true));
    }

    /**
     * @param string $page
     *
     * @return string
     */
    public function isCurrentPage($page)
    {
        $request = $this->requestStack->getMasterRequest();
        $route   = ($request !== null ? $request->attributes->get('_route') : '');
        $locale  = $this->localeConcern->get();

        return in_array($route, [$page, $page . '_' . $locale]);
    }
}