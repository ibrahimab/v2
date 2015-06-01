<?php
namespace AppBundle\Document;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;

trait BaseRepository
{
    /**
     * @var integer
     */
    protected $season;
    
    /**
     * @var string
     */
    protected $website;

    /**
     * Setting season
     *
     * @param SeasonConcern $seasonConcern
     * @return void
     */
    public function setSeason(SeasonConcern $seasonConcern)
    {
        $this->season = $seasonConcern->get();
    }

    /**
     * Getting season
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Setting website
     *
     * @param WebsiteConcern $websiteConcern
     */
    public function setWebsite(WebsiteConcern $websiteConcern)
    {
        $this->website = $websiteConcern->get();
    }

    /**
     * Getting website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }
    
    /**
     * Getting either the option passed in or the default
     *
     * @param array $options
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getOption($options, $key, $default = null)
    {
        return isset($options[$key]) ? $options[$key] : $default;
    }
}