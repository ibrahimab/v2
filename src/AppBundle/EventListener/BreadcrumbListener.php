<?php
namespace AppBundle\EventListener;

use       AppBundle\Annotation\Parser\Breadcrumb as BreadcrumbParser;
use       Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class BreadcrumbListener
{
    /**
     * @var BreadcrumbParser
     */
    private $parser;
    
    /**
     * @var \Twig_Environment
     */
    private $twig;
    
    /**
     * @param BreadcrumbParser $container
     */
    public function __construct(BreadcrumbParser $parser, \Twig_Environment $twig)
    {
        $this->parser = $parser;
        $this->twig   = $twig;
    }
    
    /**
     * @param FilterControllerEvent $event
     */
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
        
        $event->getRequest()->attributes->set('_breadcrumbs', $annotations);
    }
}