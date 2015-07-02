<?php
namespace AppBundle\Concern;

class WebsiteConcern
{
    const WEBSITE_CHALET_NL                       = 'C';
    const WEBSITE_CHALET_EU                       = 'E';
    const WEBSITE_CHALET_BE                       = 'B';
    const WEBSITE_CHALET_ONLINE_DE                = 'D';
    const WEBSITE_CHALET_TOUR_NL                  = 'T';
    const WEBSITE_SUPER_SKI                       = 'W';
    const WEBSITE_VALLANDRY_NL                    = 'V';
    const WEBSITE_VALLANDRY_COM                   = 'Q';
    const WEBSITE_ZOMERHUISJE_NL                  = 'Z';
    const WEBSITE_ZOMERHUISJE_EU                  = 'N';
    const WEBSITE_CHALETS_IN_VALLANDRY_NL         = 'V';
    const WEBSITE_CHALETS_IN_VALLANDRY_COM        = 'Q';
    const WEBSITE_ITALISSIMA_NL                   = 'I';
    const WEBSITE_ITALISSIMA_BE                   = 'K';
    const WEBSITE_VENTURASOL_NL                   = 'X';
    const WEBSITE_VENTURASOL_VACANCES_NL          = 'Y';
    const WEBSITE_ITALYHOMES_EU                   = 'H';

    const WEBSITE_TYPE_CHALET                     = 1;
    const WEBSITE_TYPE_WINTERSPORT_ACCOMMODATIONS = 2;
    const WEBSITE_TYPE_ZOMERHUISJE                = 3;
    const WEBSITE_TYPE_CHALET_TOUR                = 4;
    const WEBSITE_TYPE_CHALETS_IN_VALLANDRY       = 6;
    const WEBSITE_TYPE_ITALISSIMA                 = 7;
    const WEBSITE_TYPE_SUPER_SKI                  = 8;
    const WEBSITE_TYPE_VENTURASOL                 = 9;

    /**
     * @var string
     */
    private $website;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $types = [

        self::WEBSITE_TYPE_CHALET => [
            self::WEBSITE_CHALET_NL, self::WEBSITE_CHALET_EU, self::WEBSITE_CHALET_BE,
        ],

        self::WEBSITE_TYPE_WINTERSPORT_ACCOMMODATIONS => [],

        self::WEBSITE_TYPE_ZOMERHUISJE => [
            self::WEBSITE_ZOMERHUISJE_NL, self::WEBSITE_ZOMERHUISJE_EU,
        ],

        self::WEBSITE_TYPE_CHALET_TOUR => [
            self::WEBSITE_CHALET_TOUR_NL,
        ],

        self::WEBSITE_TYPE_CHALETS_IN_VALLANDRY => [
            self::WEBSITE_VALLANDRY_NL, self::WEBSITE_VALLANDRY_COM,
        ],

        self::WEBSITE_TYPE_ITALISSIMA => [
            self::WEBSITE_ITALISSIMA_NL, self::WEBSITE_ITALISSIMA_BE, self::WEBSITE_ITALYHOMES_EU,
        ],

        self::WEBSITE_TYPE_SUPER_SKI => [
            self::WEBSITE_SUPER_SKI,
        ],

        self::WEBSITE_TYPE_VENTURASOL => [
            self::WEBSITE_VENTURASOL_NL, self::WEBSITE_VENTURASOL_VACANCES_NL,
        ],
    ];

    /**
     * @var array
     */
    private $websites;

    /**
     * @param array $parameters
     */
    public function __construct($parameters)
    {
        $this->website  = $parameters['default_website'];
        $this->websites = $parameters['domain'];
        $this->setType();
    }

    /**
     * activate website
     */
    public function set($website)
    {
        if (array_key_exists($website, $this->websites)) {

            $this->website = $this->websites[$website]['website'];
            $this->country = $this->websites[$website]['country'];
            $this->name    = $this->websites[$website]['name'];
            $this->setType();
        }
    }

    public function get()
    {
        return $this->website;
    }

    public function type()
    {
        return $this->type;
    }

    public function setType()
    {
        foreach ($this->types as $type => $websites) {

            if (in_array($this->get(), $websites)) {

                $this->type = $type;
                break;
            }
        }
    }

    public function country()
    {
        return $this->country;
    }

    public function name()
    {
        return $this->name;
    }
}