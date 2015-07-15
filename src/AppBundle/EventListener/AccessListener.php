<?php
namespace AppBundle\EventListener;
use       AppBundle\Security\Access\BootstrapAccess;
use       AppBundle\Security\Access\Handler\Development;
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
    const ENVIRONMENT_DEVELOPMENT = 'dev';
    const ENVIRONMENT_STAGING     = 'stag';
    const ENVIRONMENT_PRODUCTION  = 'prod';

    /**
     * @var string
     */
    private $environment;

    /**
     * Constructor
     *
     * @param string $environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param GetResponseEvent $event
     * @throws AccessDeniedException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        switch ($this->environment) {

            case self::ENVIRONMENT_DEVELOPMENT:
                $handler = new Development($request);
            break;

            case self::ENVIRONMENT_STAGING:
                $handler = new Staging($request);
            break;

            default:
                throw new \Exception('Could not find requested environment');
        }

        $bootstrapAccess = new BootstrapAccess($handler, $request);
        $bootstrapAccess->check();
    }
}