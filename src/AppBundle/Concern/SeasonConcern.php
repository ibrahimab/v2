<?php
namespace AppBundle\Concern;

use       Symfony\Component\HttpFoundation\RequestStack;

class SeasonConcern
{
    /**
     * Winter season definition
     */
    const SEASON_WINTER  = 1;
    
    /**
     * Summer season definition
     */
    const SEASON_SUMMER  = 2;
    
    /**
     * @var array
     */
    private $domains;
    
    /**
     * @var integer
     */
    private $season;
    
    /**
     * @param array $parameters
     */
    public function __construct($parameters)
    {
        $this->domains = $parameters['domain'];
        $this->season  = $parameters['default_season'];
    }
    
    /**
     * @param string $hostname
     * @return void
     */
    public function set($hostname)
    {
        if (array_key_exists($hostname, $this->domains)) {
            $this->season = $this->domains[$hostname]['season'];
        }
    }
    
    /**
     * Get season
     *
     * @return integer
     */
    public function get()
    {
        return $this->season;
    }
}