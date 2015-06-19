<?php
namespace AppBundle\EventListener;
use       AppBundle\Concern\WebsiteConcern;
use       Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class HostnameListener
{
    /**
     * @var WebsiteConcern
     */
    private $websiteConcern;
    
    /**
     * Injecting DomainConcern into this listener
     *
     * @param WebsiteConcern $websiteConcern
     */
    public function __construct(WebsiteConcern $websiteConcern)
    {
        $this->websiteConcern = $websiteConcern;
    }
    
    /**
     * This event is called before every controller to set the website used
     * onto the request attributes
     * 
     * @param FilterControllerEvent $event
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }
        
        $request = $event->getRequest();
        $this->websiteConcern->set($request->getHost());
        $request->attributes->set('_website', $this->websiteConcern->get());
    }
}