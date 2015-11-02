<?php
namespace AppBundle\Twig\Extension;

use       AppBundle\Service\UtilsService;

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
        return UtilsService::bbcode($text);
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
    public function tokenize($value, $filter = null)
    {
        // if second parameter is null, that means we want to tokenize a filter
        // otherwise tokenize the value
        return (null === $filter ? $this->filterService->tokenize($value) : $this->filterService->tokenize($filter, $value));
    }
}