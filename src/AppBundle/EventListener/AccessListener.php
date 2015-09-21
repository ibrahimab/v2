<?php
namespace AppBundle\EventListener;
use       AppBundle\Security\Access\BootstrapAccess;
use       AppBundle\Security\Access\Handler\AccessHandlerInterface;
use       AppBundle\Security\Access\Handler\Development;
use       AppBundle\Security\Access\Handler\Staging;
use       Symfony\Component\HttpFoundation\Request;
use       Symfony\Component\Security\Core\Exception\AccessDeniedException;
use       Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class AccessListener
{
    /**
     * @var AccessHandlerInterface
     */
    private $accessHandler;

    /**
     * Constructor
     *
     * @param string $environment
     */
    public function __construct(AccessHandlerInterface $accessHandler)
    {
        $this->accessHandler = $accessHandler;
    }

    /**
     * @param GetResponseEvent $event
     * @throws AccessDeniedException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        $bootstrapAccess = new BootstrapAccess($this->accessHandler, $request);
        $bootstrapAccess->check();
    }
}