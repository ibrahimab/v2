<?php
namespace AppBundle\Entity;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet.nl
 * @version 0.0.4
 * @since   0.0.4
 */
trait BaseRepositoryTrait
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
     * @var string
     */
    protected $websiteConcern;
    
    /**
     * @var string
     */
    protected $locale;

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
        $this->websiteConcern = $websiteConcern;
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
     * @return WebsiteConcern
     */
    public function getWebsiteConcern()
    {
        return $this->websiteConcern;
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

    /**
     * Getting locale field
     *
     * @param string $field
     * @param string $locale
     * @return string
     */
    public function getLocaleField($field, $locale = null)
    {
        $locale = (null === $locale ? $this->getLocale() : strtolower($locale));
        switch ($locale) {

            case 'en':
                $localeField = 'english' . ucfirst($field);
                break;

            case 'de':
                $localeField = 'german' . ucfirst($field);
                break;

            case 'nl':
            default:
                $localeField = $field;
                break;
        }

        return $localeField;
    }
}