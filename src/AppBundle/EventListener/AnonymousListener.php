<?php
namespace AppBundle\EventListener;

use AppBundle\Service\UtilsService;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * AnonymousUniqueTokenListener
 *
 * This class creates a new unique token for a anonymous user
 * and saves it to a cookie for a long time. This is the unique identifier
 * used to track anonymous users to save data for.
 *
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @since   0.0.2
 */
class AnonymousListener
{
	/**
	 * @var ContainerInterface
	 */
	protected $container;

    /**
     * @var string
     */
    protected $token;

	/**
	 * Injecting the service container
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
        $this->token     = null;
	}

    /**
     * @param FilterControllerEvent $event
     */
	public function onKernelRequest(GetResponseEvent $event)
	{
        if (!$event->isMasterRequest()) {
            return;
        }

        $cookies     = $event->getRequest()->cookies;
        $anonymous   = $this->container->get('security.token_storage')->getToken();
        $userService = $this->container->get('app.api.user');

        if (null === $anonymous) {
            return;
        }

        if ($cookies->has('_anon_tk')) {

            $this->token = $cookies->get('_anon_tk');

            if (null === $userService->get($this->token)) {
                $userService->create($this->token);
            }

        } else {

            $secret      = $this->container->getParameter('secret');
            $this->token = UtilsService::generateToken($secret);

            $userService->create($this->token);
        }

        $anonymous->setAttribute('_anon_tk', $this->token);

        $javascriptService = $this->container->get('app.javascript');
        $javascriptService->set('app.user', $userService->user());
	}

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $cookies = $request->cookies;
        $anonymous = $this->container->get('security.token_storage')->getToken();

        if (null === $anonymous) {
            return;
        }

        if (false === $cookies->has('_anon_tk')) {

            $response    = $event->getResponse();
            $sslEnabled  = $this->container->getParameter('ssl_enabled');
            $domain      = $request->server->get('HTTP_HOST');

            $expiration  = new \DateTime();
            $expiration->add(new \DateInterval('P2Y'));

            $tokenCookie = new Cookie('_anon_tk', $this->token,  $expiration, '/', $domain, ($sslEnabled === true));
            $response->headers->setCookie($tokenCookie);
        }
    }
}