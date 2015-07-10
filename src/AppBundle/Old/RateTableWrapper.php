<?php
namespace AppBundle\Old;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       Symfony\Component\DependencyInjection\ContainerInterface;
use       Doctrine\DBAL\Connection;

/**
 * RateTable wrapper
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class RateTableWrapper
{
    /**
     * @const int
     */
    const SHOW_KNOWN_RATES = 3;

    /**
     * @const string
     */
    const VALUTA_EURO      = 'euro';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TypeServiceEntityInterface
     */
    private $type;

    /**
     * @var Connection
     */
    private $doctrine;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var SeasonConcern
     */
    private $season;

    /**
     * @var WebsiteConcern
     */
    private $website;

    /**
     * @var array
     */
    private $seasonRates;

    /**
     * @var string
     */
    private $html;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param TypeServiceEntityInterface $type
     * @return RateTableWrapper
     */
    public function setType(TypeServiceEntityInterface $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return TypeServiceEntityInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        if (null === $this->locale) {
            $this->locale = $this->container->get('request')->getLocale();
        }

        return $this->locale;
    }

    /**
     * @return Connection
     */
    public function getDoctrine()
    {
        if (null === $this->doctrine) {
            $this->doctrine = $this->container->get('doctrine.orm.entity_manager')->getConnection();
        }

        return $this->doctrine;
    }

    /**
     * @return SeasonConcern
     */
    public function getSeason()
    {
        if (null === $this->season) {
            $this->season = $this->container->get('app.concern.season');
        }

        return $this->season;
    }

    /**
     * @return WebsiteConcern
     */
    public function getWebsite()
    {
        if (null === $this->website) {
            $this->website = $this->container->get('app.concern.website');
        }

        return $this->website;
    }

    /**
     * @return array
     */
    public function getUnknownRates()
    {
        $doctrine  = $this->getDoctrine();
        $statement = $doctrine->prepare('SELECT DISTINCT tp.seizoen_id, s.naam' . ($this->getLocale() === 'nl' ? '' : $this->getLocale()) . ' AS naam,
                                         s.optietarieventonen, UNIX_TIMESTAMP(s.begin) AS begin, UNIX_TIMESTAMP(s.eind) AS eind
                                         FROM seizoen s, tarief_personen tp
                                         WHERE s.tonen > 1
                                         AND s.type = :season
                                         AND s.seizoen_id = tp.seizoen_id
                                         AND tp.type_id = :type
                                         ORDER BY s.begin, s.eind');

        $statement->execute(['type' => $this->getType()->getId(), 'season' => $this->getSeason()->get()]);
        return $statement->fetchAll();
    }

    /**
     * @return array
     */
    public function getKnownRates()
    {
        $doctrine  = $this->getDoctrine();
        $statement = $doctrine->prepare('SELECT DISTINCT t.seizoen_id, s.naam' . ($this->getLocale() === 'nl' ? '' : $this->getLocale()) . ' AS naam,
                                         s.optietarieventonen, UNIX_TIMESTAMP(s.begin) AS begin, UNIX_TIMESTAMP(s.eind) AS eind
                                         FROM seizoen s, tarief t
                                         WHERE s.tonen > 1
                                         AND s.type = :season
                                         AND s.seizoen_id = t.seizoen_id
                                         AND t.type_id = :type
                                         ORDER BY s.begin, s.eind');

        $statement->execute(['type' => $this->getType()->getId(), 'season' => $this->getSeason()->get()]);
        return $statement->fetchAll();
    }

    /**
     * @return array
     */
    public function getSeasonRates()
    {
        if (null === $this->seasonRates) {
            $this->seasonRates = $this->getType()->getAccommodation()->getShow() === self::SHOW_KNOWN_RATES ? $this->getKnownRates() : $this->getUnknownRates();
        }

        return $this->seasonRates;
    }

    /**
     * @return string
     */
    public function render()
    {
        if (null === $this->html) {

            $constants = $this->container->get('old.constants');
            $constants->setup();

            $path = (dirname(dirname(__DIR__))) . '/old-classes';
            $path = readlink($path);
            $path = dirname(dirname($path));

            $GLOBALS['taal'] = $this->getLocale();
            $GLOBALS['websitetype'] = $this->getWebsite()->type();
            $GLOBALS['website'] = $this->getWebsite()->get();
            $GLOBALS['websiteland'] = $this->getWebsite()->country();
            $GLOBALS['website'] = ['type' => $this->getwebsite()->type(), 'website' => $this->getWebsite()->get(), 'country' => $this->getWebsite()->country()];
            
            $vars = ['websitetype' => $this->getWebsite()->type(), 'websitenaam' => $this->getWebsite()->name(), 'ttv' => ($this->getLocale() === 'nl' ? '' : '_' . $this->getLocale())];
            
        	include $path . '/content/_teksten.php';
            include_once $path . '/content/_teksten_intern.php';

            $GLOBALS['txt']  = $txt;
            $GLOBALS['txta'] = $txta;

            include_once dirname(dirname(dirname(__DIR__))) . '/app/bc_functions.php';

            $seasonRates                   = $this->getSeasonRates();
            $seasonRates                   = (count($seasonRates) > 0 ? $seasonRates[0] : []);
            $table                         = $this->container->get('old.rate.table');
            $translator                    = $this->container->get('translator');
            $table->type_id                = $this->getType()->getId();
            $table->setRedis(new \wt_redis(new Logger()));
            $table->show_afwijkend_legenda = true;
            $table->seizoen_id             = '0' . (isset($seasonRates['seizoen_id']) ? (',' . $seasonRates['seizoen_id']) : '');

            if ($this->getWebsite()->get() === WebsiteConcern::WEBSITE_CHALET_EU) {

                $table->meerdere_valuta = true;
                $table->actieve_valuta  = self::VALUTA_EURO;
            }

            // $tabel = $this->container->get('old.rate.table');
            // $tabel->toon_interne_informatie = true;
            // $tabel->toon_beschikbaarheid = true;
            // $tabel->toon_commissie = true;
            // $tabel->meerdere_valuta = true;
            // $tabel->setRedis(new \wt_redis(new Logger()));
            // $tabel->actieve_valuta = "euro";
            // $tabel->seizoen_id=27;
            // $tabel->type_id=5940;

            $this->html = $table->toontabel();
        }

        return $this->html;
    }
}