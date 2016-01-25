<?php

namespace AppBundle\EventListener;

use AppBundle\Concern\LocaleConcern;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * LocaleListener
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class LocaleListener
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $domains;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var LocaleConcern
     */
    protected $localeConcern;

    const LOCALE_NL = 'nl';
    const LOCALE_EN = 'en';
    const LOCALE_DE = 'de';

    protected $systemLocales = [

        self::LOCALE_NL => 'nl_NL',
        self::LOCALE_EN => 'en_GB',
        self::LOCALE_DE => 'de_DE',
    ];

    /**
     * @param array $parameters
     * @param string $defaultLocale
     */
    public function __construct(Array $parameters, LocaleConcern $localeConcern)
    {
        $this->parameters    = $parameters;
        $this->domains       = $parameters['domain'];
        $this->defaultLocale = $parameters['default_locale'];
        $this->localeConcern = $localeConcern;
    }

    /**
     * {@InheritDoc}
     *
     * This method sets up the locale based on the domain used
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        // get domain
        $domain = $request->getHost();
        $locale = $this->defaultLocale;

        if (array_key_exists($domain, $this->domains)) {
            $locale = $this->domains[$domain]['locale'];
        }

        $request->setLocale($locale);
        $this->localeConcern->set($locale);

        setlocale(LC_ALL, $this->systemLocales[$locale]);
    }
}