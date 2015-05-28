<?php
namespace AppBundle\EventListener;
use		  Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use		  Symfony\Component\DependencyInjection\ContainerInterface;
use       Symfony\Component\Security\Core\Util\SecureRandom;
use       Symfony\Component\HttpFoundation\Cookie;

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
	 * Injecting the service container
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}
	
	public function onKernelResponse(FilterResponseEvent $event)
	{
		$response  = $event->getResponse();
        $request   = $event->getRequest();
        $cookies   = $request->cookies;
        $domain    = $event->getRequest()->server->get('HTTP_HOST');
        $anonymous = $this->container->get('security.context')->getToken();
        $secret    = $this->container->getParameter('secret');

        if (false === $cookies->has('_anon_tk')) {
            
            $expiration  = new \DateTime();
            $expiration->add(new \DateInterval('P2Y'));
            
            $session     = $this->container->get('session');
            $session->start();
            
            $token       = $this->container->get('service.utils')->generateToken();
            $tokenCookie = new Cookie('_anon_tk', mt_rand() . '|' . $token,  $expiration, '/', $domain, true);
            
            $response->headers->setCookie($tokenCookie);
            
        } else {
            
            exit('test');
            $parts   = explode('.', $cookies->get('_anon_tk'));
            $mongoId = null;
            
            if (count($parts) > 0) {
                $mongoId = $parts[0];
            }
            
            dump($mongoId);
            
            if ($hmac !== hash_hmac('sha256', $mongoId, $secret)) {
                $cookies->remove('_anon_tk');
            }
        }
        
        $anonymous->setAttribute('_anon_tk', $cookies->get('_anon_tk'));
        dump($anonymous);
        // exit;
	}
}