<?php
namespace AppBundle\EventListener;

use       AppBundle\Concern\SeasonConcern;
use       Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class SeasonListener
{
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
        
        $attributes = $event->getRequest()->attributes;
        $attributes->set('_season', SeasonConcern::SEASON_WINTER);
    }
}