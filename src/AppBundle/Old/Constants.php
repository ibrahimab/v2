<?php
use AppBundle\Old;
use Symfony\Component\DependencyInject\ContainerInterface;

/**
 * Define constants needed by old website
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Constants
{
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
        // var_dump('test2');exit;
    }
    
    public function setup()
    {
        $this->setupDatabase();
        $this->setupRedis();
    }
    
    public function setupDatabase()
    {
        if (!defined('wt_db_host')) {
            define('wt_db_host', $container->getParameter('database_host'));
        }

        if (!defined('wt_db_user')) {
            define('wt_db_user', $container->getParameter('database_user'));
        }

        if (!defined('wt_db_password')) {
            define('wt_db_password', $container->getParameter('database_password'));
        }
    }
    
    public function setupRedis()
    {
        if (!defined('wt_redis_host')) {
            define('wt_redis_host', $container->getParameter('redis_server'));
        }
    }
}