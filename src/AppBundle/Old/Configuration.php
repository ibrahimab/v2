<?php
namespace AppBundle\Old;
use       Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration class
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class Configuration
{
    /**
     * @var array
     */
    protected $config;
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $website         = $container->get('app.concern.website');
        $locale          = $container->get('request')->getLocale();
        $this->container = $container;
        $this->config    = [
            
            'path'                            => null,
            'seizoentype'                     => $this->container->get('app.concern.season')->get(),
            'website'                         => $website->get(),
            'websitetype'                     => $website->type(),
            'websitenaam'                     => null,
            'wederverkoop'                    => null,
            'livechat_code'                   => null,
            'taal'                            => $locale,
            'reserveringskosten'              => null,
            'lokale_testserver'               => null,
            'chalettour_aanpassing_commissie' => null,
            'ttv'                             => null,
            'unixdir'                         => null,
            'soortaccommodatie'               => null,
            'wt_htmlentities_cp1252'          => null,
            'basehref'                        => null,
            'isMobile'                        => false,
            'voorkant_cms'                    => false,
        ];
    }
    
    /**
     * Magic getter function
     *
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        }
        
        throw new \Exception(sprintf('Configuration key does not exist: (%s)', $name));
    }
}