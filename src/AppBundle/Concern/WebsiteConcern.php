<?php
namespace AppBundle\Concern;

class WebsiteConcern
{
    const WEBSITE_CHALET_NL                = 'C';
    const WEBSITE_CHALET_EU                = 'E';
    const WEBSITE_CHALET_BE                = 'B';
    const WEBSITE_CHALET_ONLINE_DE         = 'D';
    const WEBSITE_CHALET_TOUR_NL           = 'T';
    const WEBSITE_SUPER_SKI                = 'W';
    const WEBSITE_VALLANDRY_NL             = 'V';
    const WEBSITE_VALLANDRY_COM            = 'Q';
    const WEBSITE_ZOMERHUISJE_NL           = 'Z';
    const WEBSITE_ZOMERHUISJE_EU           = 'N';
    const WEBSITE_CHALETS_IN_VALLANDRY_NL  = 'V';
    const WEBSITE_CHALETS_IN_VALLANDRY_COM = 'Q';
    const WEBSITE_ITALISSIMA_NL            = 'I';
    const WEBSITE_ITALISSIMA_BE            = 'K';
    const WEBSITE_VENTURASOL_NL            = 'X';
    const WEBSITE_VENTURASOL_VACANCES_NL   = 'Y';
    const WEBSITE_ITALYHOMES_EU            = 'H';
    
    /**
     * @var string
     */
    private $website;
    
    /**
     * @var array
     */
    private $websites;
    
    public function __construct($parameters)
    {
        $this->website  = $parameters['default_website'];
        $this->websites = $parameters['domain'];
    }
    
    /**
     * activate website
     */
    public function set($website)
    {
        if (array_key_exists($website, $this->websites)) {
            $this->website = $this->websites[$website]['website'];
        }
    }
    
    public function get()
    {
        return $this->website;
    }
}