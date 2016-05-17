<?php
namespace AppBundle\Service\Api\Search\Repository;

use AppBundle\Concern\LocaleConcern;
use AppBundle\Concern\WebsiteConcern;
use AppBundle\Concern\SeasonConcern;
use AppBundle\Service\Api\Search\Params;
use Doctrine\DBAL\Connection;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class FilterNamesRepository implements FilterNamesRepositoryInterface
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var LocaleConcern
     */
    private $locale;

    /**
     * @var WebsiteConcern
     */
    private $website;

    /**
     * @var SeasonConcern
     */
    private $season;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db, LocaleConcern $locale, WebsiteConcern $website, SeasonConcern $season)
    {
        $this->db      = $db;
        $this->locale  = $locale;
        $this->website = $website;
        $this->season  = $season;
    }

    /**
     * @param Params $params
     *
     * @return array
     */
    public function get(Params $params)
    {
        $countries      = $params->getCountries();
        $regions        = $params->getRegions();
        $places         = $params->getPlaces();
        $accommodations = $params->getAccommodations();
        $suppliers      = $params->getSuppliers();
        $results        = ['accommodations' => [], 'regions' => [], 'places' => [], 'countries' => []];

        if (false !== $countries) {
            $results['countries'] = $this->countries($countries);
        }

        if (false !== $regions) {
            $results['regions'] = $this->regions($regions);
        }

        if (false !== $places) {
            $results['places'] = $this->places($places);
        }

        if (false !== $accommodations) {
            $results['accommodations'] = $this->accommodations($accommodations);
        }

        return $results;
    }

    /**
     * @return array
     */
    public function countries($countries)
    {
        return $this->results("SELECT {$this->getLocaleField('seonaam')} AS id, {$this->getLocaleField('naam')} AS label
                               FROM   land
                               WHERE  {$this->getLocaleField('seonaam')} IN ({$this->stringArrayQuery($countries)})");
    }

    /**
     * @return array
     */
    public function regions($regions)
    {
        return $this->results("SELECT skigebied_id AS id, {$this->getLocaleField('naam')} AS label
                               FROM   skigebied
                               WHERE  skigebied_id IN ({$this->integerArrayQuery($regions)})
                               AND    FIND_IN_SET(:website, websites) > 0
                               AND    wzt = :season", ['website' => $this->website->get(),
                                                       'season'  => $this->season->get()]);
    }

    /**
     * @return array
     */
    public function places($places)
    {
        return $this->results("SELECT plaats_id AS id, {$this->getLocaleField('naam')} AS label
                               FROM   plaats
                               WHERE  plaats_id IN ({$this->integerArrayQuery($places)})
                               AND    FIND_IN_SET(:website, websites) > 0
                               AND    wzt = :season", ['website' => $this->website->get(),
                                                       'season'  => $this->season->get()]);
    }

    /**
     * @return array
     */
    public function accommodations($accommodations)
    {
        return $this->results("SELECT accommodatie_id AS id, naam AS label
                               FROM   accommodatie
                               WHERE  accommodatie_id IN ({$this->integerArrayQuery($accommodations)})");
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public function results($query, $data = [])
    {
        $data = $this->db->fetchAll($query, $data);

        return array_column($data, 'label', 'id');
    }

    /**
     * @param array $array
     *
     * @return string
     */
    private function integerArrayQuery(array $array)
    {
        return implode(', ', $array);
    }

    /**
     * @param array $array
     *
     * @return string
     */
    private function stringArrayQuery(array $array)
    {
        return implode(', ', array_map(function($value) {
            return "'" . addslashes($value) . "'";
        }, $array));
    }

    /**
     * @return string
     */
    private function getLocaleField($field)
    {
        $locale = $this->locale->get();
        return $field . ($locale === 'nl' ? '' : ('_' . $locale));
    }
}