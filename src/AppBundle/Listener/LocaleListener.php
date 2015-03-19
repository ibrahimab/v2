<?php
namespace AppBundle\Listener;

use       Symfony\Component\HttpKernel\Event\GetResponseEvent;
use       Symfony\Component\HttpKernel\KernelEvents;
use       Symfony\Component\EventDispatcher\EventSubscriberInterface;
use       Symfony\Component\DependencyInjection\ContainerInterface;

class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var array
     */
    protected $domainLocales;
    
    /**
     * @var string
     */
    protected $defaultLocale;
    
    /**
     * @param ContainerInterface $container
     * @param string $defaultLocale
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->domainLocales = $container->getParameter('app')['domain_locales'];
        $this->defaultLocale = $container->getParameter('app')['default_locale'];
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
        
        if (array_key_exists($domain, $this->domainLocales)) {
            $locale = $this->domainLocales[$domain];
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