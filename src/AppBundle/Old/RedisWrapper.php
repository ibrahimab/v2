<?php
namespace AppBundle\Old;
use       Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Redis wrapper
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class RedisWrapper
{
    /**
     * @const string
     */
    const MONITOR_REDIS_KEY = 'monitor_redis';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $constants = $this->container->get('old.constants');
        $constants->setup();

        $this->redis = $this->container->get('old.redis');
    }

    /**
     * Monitor redis
     *
     * @return boolean
     */
    public function monitor()
    {
        $hash = time() . '-' . md5('monitor-redis-' . time());
        $this->redis->set(self::MONITOR_REDIS_KEY, $hash);

        $comparisonHash = $this->redis->get(self::MONITOR_REDIS_KEY);

        return $hash === $comparisonHash;
    }
}