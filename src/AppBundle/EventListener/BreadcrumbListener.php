<?php
namespace AppBundle\EventListener;

use       AppBundle\Annotation\Parser\Breadcrumb as BreadcrumbParser;
use       Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use       Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BreadcrumbListener
{
    /**
     * @var BreadcrumbParser
     */
    private $parser;
    
    /**
     * @param BreadcrumbParser $container
     */
    public function __construct(BreadcrumbParser $parser)
    {
        $this->parser = $parser;
    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }
        
        $controllerCallable = $event->getController();
        $controller         = $controllerCallable[0];
        $method             = $controllerCallable[1];
        
        // parse controller
        $annotations = $this->parser->parse($controller, $method);
        dump($annotations);exit;
    }
    
    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        //$breadcrumbPlaceHolders = $event->getRequest()->attributes;
    }
}