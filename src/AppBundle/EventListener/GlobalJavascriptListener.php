<?php
namespace AppBundle\EventListener;

use       AppBundle\Service\Javascript\JavascriptService;
use		  AppBundle\Service\UtilsService;
use       Symfony\Component\HttpKernel\Event\FilterControllerEvent;

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
    public function __construct(JavascriptService $javascriptService, UtilsService $utilsService)
    {
        $this->javascriptService = $javascriptService;
		$this->utilsService		 = $utilsService;
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

        $this->javascriptService->set('app.locale',           $request->getLocale());
        $this->javascriptService->set('app.season',           $attributes->get('_season'));
        $this->javascriptService->set('app.website',          $attributes->get('_website'));
        $this->javascriptService->set('app.controller.short', $this->utilsService->normalizeController($attributes->get('_controller')));
		$this->javascriptService->set('app.controller.full',  $attributes->get('_controller'));
        $this->javascriptService->set('app.route.name',   	  $attributes->get('_route'));
        $this->javascriptService->set('app.route.params', 	  $attributes->get('_route_params'));
    }
}