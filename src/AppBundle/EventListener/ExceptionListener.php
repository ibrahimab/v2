<?php
namespace AppBundle\EventListener;

use AppBundle\Concern\LocaleConcern;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExceptionListener
{
    /**
     *@var Router
     */
    private $router;

    /**
     *@var LocaleConcern
     */
    private $locale;

    /**
     * @param Router
     */
    public function __construct(Router $router, LocaleConcern $locale)
    {
        $this->router = $router;
        $this->locale = $locale;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @return void
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request   = $event->getRequest();

        if ($exception instanceof NotFoundHttpException) {

            $path  = $request->getRequestUri();
            $parts = explode('/', $path);
            $parts = array_filter($parts, function($part) {
                return $part !== '';
            });

            if (count($parts) === 1 && strpos($path, '.') === false) {

                $locale = $this->locale->get();
                $url    = $this->router->generate('search_' . $locale, ['fs' => current($parts)]);

                $response = new RedirectResponse($url);
                $event->setResponse($response);
            }
        }
    }
}
