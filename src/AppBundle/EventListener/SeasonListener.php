<?php
namespace AppBundle\EventListener;

use       AppBundle\Concern\SeasonConcern;
use       Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class SeasonListener
{
    /**
     * @var SeasonConcern
     */
    private $seasonConcern;
    
    /**
     * Injecting SeasonConcern into this listener
     *
     * @param SeasonConcern $seasonConcern
     */
    public function __construct(SeasonConcern $seasonConcern)
    {
        $this->seasonConcern = $seasonConcern;
    }
    
    /**
     * This event is called before every controller to set the season used
     * onto the request attributes AND the global javascript object
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
        $this->seasonConcern->set($request->getHost());
        $request->attributes->set('_season', $this->seasonConcern->get());
    }
}