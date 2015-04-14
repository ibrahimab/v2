<?php
namespace AppBundle\EventListener;

use       Symfony\Component\HttpKernel\Event\GetResponseEvent;
use       Symfony\Component\HttpKernel\KernelEvents;
use       Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
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
     * @param array $parameters
     * @param string $defaultLocale
     */
    public function __construct($parameters)
    {
        $this->parameters    = $parameters;
        $this->domains       = $parameters['domain'];
        $this->defaultLocale = $parameters['default_locale'];
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
    }
    
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 18]],
        ];
    }
}