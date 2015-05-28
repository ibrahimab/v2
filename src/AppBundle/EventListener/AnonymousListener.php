<?php
namespace AppBundle\EventListener;
use		  Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use		  Symfony\Component\DependencyInjection\ContainerInterface;

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
		$response = $event->getResponse();
		dump('test');
	}
}