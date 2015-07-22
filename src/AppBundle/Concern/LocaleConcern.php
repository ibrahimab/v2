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
    public function __construct(RequestStack $requestStack)
    {
        if (null !== ($request = $requestStack->getCurrentRequest())) {
            $this->locale = $request->getLocale();
        }
    }

    /**
     * @return string
     */
    public function get()
    {
        return $this->locale;
    }
}