<?php
namespace AppBundle\Concern;
use       Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class LocaleConcern
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @param RequestStack
     */
    public function __construct($default)
    {
        $this->locale = $default;
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     */
    public function set($locale)
    {
        $this->locale = $locale;
    }
}