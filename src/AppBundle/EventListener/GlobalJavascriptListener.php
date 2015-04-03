<?php
namespace AppBundle\EventListener;

use       Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use       AppBundle\Service\Javascript\JavascriptService;

class GlobalJavascriptListener
{
    /**
     * @var JavascriptService
     */
    protected $javascriptService;
    
    /**
     * Injecting the javascript service
     * 
     * @param JavascriptService $javascriptService
     */
    public function __construct(JavascriptService $javascriptService)
    {
        $this->javascriptService = $javascriptService;
    }
    
    /**
     * This event is called before every controller to set some global
     * default values in the javascript service.
     * 
     * @param FilterControllerEvent $event
     * @return void
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }
        
        $request    = $event->getRequest();
        $attributes = $request->attributes;
        
        $this->javascriptService->set('app.locale', $request->getLocale());
        $this->javascriptService->set('app.controller', $attributes->get('_controller'));
        $this->javascriptService->set('app.route.name', $attributes->get('_route'));
        $this->javascriptService->set('app.route.params', $attributes->get('_route_params'));
    }
}