<?php
namespace AppBundle\Security\Access;
use       AppBundle\Security\Access\Handler\AccessHandlerInterface;
use       Symfony\Component\HttpFoundation\Request;
use       Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class BootstrapAccess
{
    /**
     * @var AccessHandlerInterface
     */
    private $handler;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param AccessHandlerInterface $handler
     */
    public function __construct(AccessHandlerInterface $handler, Request $request)
    {
        $this->handler = $handler;
        $this->request = $request;
    }

    /**
     * @return boolean
     * @throws AccessDeniedException
     */
    public function check()
    {
        if (false === $this->handler->handle($this->request)) {
            throw new AccessDeniedHttpException('You are not allowed to access this environment!');
        }

        return true;
    }
}