<?php
namespace AppBundle\Entity;

/**
 * Video entity
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Video
{
    /**
     * @var string
     */
    private $url;

    /**
     * Constructor
     *
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $url
     * @return Video
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}