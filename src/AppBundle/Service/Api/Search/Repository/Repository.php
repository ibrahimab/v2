<?php
namespace AppBundle\Service\Api\Search\Repository;

use AppBundle\Concern\LocaleConcern;
use AppBundle\Concern\WebsiteConcern;
use AppBundle\Concern\SeasonConcern;
use AppBundle\Service\Api\Search\Params;
use AppBundle\Service\Api\Search\SearchService;
use AppBundle\Service\Api\Search\Builder\Where;
use AppBundle\Service\Api\Search\Builder\Builder as SearchBuilder;
use AppBundle\Service\Api\Search\Filter\Builder  as FilterBuilder;
use AppBundle\Service\Api\Search\Filter\Manager  as FilterManager;
use AppBundle\Service\Api\Search\Filter\Filter;
use Doctrine\DBAL\Connection;
use PDO;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 1.0.0
 * @since   1.0.0
 */
class Repository implements RepositoryInterface
{
    /**
     * @var PDO
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
     * @var array
     */
    private $config;

    /**
     * Constructor
     *
     * @param Connection     $db
     * @param LocaleConcern  $locale
     * @param WebsiteConcern $website
     * @param SeasonConcern  $season
     * @param array          $config
     */
    public function __construct(Connection $db, LocaleConcern $locale, WebsiteConcern $website, SeasonConcern $season, array $config)
    {
        $this->db      = $db;
        $this->locale  = $locale;
        $this->website = $website;
        $this->season  = $season;
        $this->config  = $config;
    }

    /**
     * @param Params $params
     *
     * @return array
     */
    public function search(SearchBuilder $searchBuilder, FilterBuilder $filterBuilder)
    {
        $weekend = $searchBuilder->getClause(Where::WHERE_WEEKEND);
        $persons = $searchBuilder->getClause(Where::WHERE_PERSONS);

        if (false === $weekend && false === $persons) {

            // no weekend or person is selected
            // no extra query additions needed
            $results = $this->fetch($searchBuilder, $filterBuilder);
        }

        if (false === $weekend && false !== $persons) {

            // only persons is selected, nothing special, same as above
            //  keeping this if statement just for semantics and documentation
            $results = $this->fetch($searchBuilder, $filterBuilder);
        }

        if (false !== $weekend && false === $persons) {

            // only weekend selected
            $extra   = $this->buildExtraWithWeekend($weekend->getValue());
            $results = $this->fetch($searchBuilder, $filterBuilder, $extra);
        }

        if (false !== $weekend && false !== $persons) {

            // both weekend and persons was selected
            // query for arrangement and accommodation separately

            // arrangement needs weekend and persons to fetch results
            $extraAssortiment    = $this->buildExtraWithWeekendAndPersonsForAssortiments($weekend->getValue(), $persons->getValue());
            $assortiments        = $this->fetch($searchBuilder, $filterBuilder, $extraAssortiment);

            // accommodations only needs weekend
            $extraAccommodations = $this->buildExtraWithWeekendForAccommodations($weekend->getValue());
            $accommodations      = $this->fetch($searchBuilder, $filterBuilder, $extraAccommodations);

            // merge results
            $results             = array_merge($assortiments, $accommodations);
        }

        return $results;
    }

    /**
     * @param SearchBuilder $searchBuilder
     * @param FilterBuilder $filterBuilder
     *
     * @return array
     */
    public function fetch(SearchBuilder $searchBuilder, FilterBuilder $filterBuilder, $extra = false)
    {
        $query = "SELECT DISTINCT t.type_id AS type_id, t.naam AS type_name, t.optimaalaantalpersonen AS optimal_residents, t.maxaantalpersonen AS max_residents, IF(a.toonper = 1, 'arrangement', 'accommodation') AS type,
                         t.apart_tonen_in_zoekresultaten AS separate_in_search, t.kwaliteit AS type_quality, IF(t.kwaliteit > 0, t.kwaliteit, a.kwaliteit) AS quality, 0 AS offer, t.slaapkamers AS bedrooms, t.badkamers AS bathrooms,
                         0 AS surveyCount, t.zoekvolgorde AS type_search_order,
                         a.accommodatie_id AS accommodation_id, {$this->getLocaleField('a.naam')} AS accommodation_name,
                         {$this->getLocaleField('a.korteomschrijving')} AS accommodation_short_description, a.kenmerken AS accommodation_features,
                         a.kwaliteit AS accommodation_quality, a.afstandpiste AS accommodation_distance_slope, a.zoekvolgorde AS accommodation_search_order,
                         a.toonper as accommodation_show, a.soortaccommodatie AS kind, p.plaats_id AS place_id, {$this->getLocaleField('p.naam')} AS place_name,
                         {$this->getLocaleField('p.seonaam')} AS place_seoname, p.kenmerken as place_features, r.skigebied_id AS region_id, {$this->getLocaleField('r.naam')} AS region_name,
                         {$this->getLocaleField('r.seonaam')} AS region_seoname, r.kilometerpiste AS region_total_slopes_distance, c.land_id AS country_id, {$this->getLocaleField('c.naam')} AS country_name,
                         {$this->getLocaleField('c.seonaam')} as country_seoname, c.begincode AS country_countrycode,
                         s.zoekvolgorde AS supplier_search_order";

        $clauses = $this->buildClauses($searchBuilder);
        $clauses = array_merge($clauses, $this->buildFilters($filterBuilder));

        if (false !== $extra) {

            // setting up extra columns
            $query .= ',' . $extra['columns'];
        }

        $query .= " FROM type t, accommodatie a, plaats p, skigebied r, land c, leverancier s";

        if (false !== $extra) {

            // setting up extra tables
            $query .= ', ' . $extra['tables'];
        }

        $query .= " WHERE  t.accommodatie_id    = a.accommodatie_id
                    AND    s.leverancier_id     = t.leverancier_id
                    AND    c.land_id            = p.land_id
                    AND    p.plaats_id          = a.plaats_id
                    AND    p.skigebied_id       = r.skigebied_id
                    AND    a.tonen              = :display
                    AND    a.tonenzoekformulier = :displayInSearch
                    AND    t.tonen              = :display
                    AND    t.tonenzoekformulier = :displayInSearch
                    AND    FIND_IN_SET(:website, t.websites) > 0";

        if (false !== $extra) {

            // setting up extra clauses
            $query .= $extra['clauses'];
        }

        $parameters = [];
        $groups     = ['main' => 'AND', 'region_place_country' => 'OR', 'accommodation_freesearch' => 'OR'];
        $parts      = [];

        foreach ($clauses as $clause) {

            $parts[$clause['group_id']][] = ' ( ' . $clause['sql'] . ' )';

            foreach ($clause['parameters'] as $identifier => $parameter) {
                $parameters[] = $parameter;
            }
        }

        foreach ($parts as $groupId => $part) {
            $query .= ' AND (' . implode(' ' . $groups[$groupId], $part) . ' )';
        }

        if (false !== $extra) {

            foreach ($extra['parameters'] as $parameter) {
                $parameters[] = $parameter;
            }
        }

        $statement = $this->db->prepare($query);
        $statement->bindValue('display', 1, PDO::PARAM_INT);
        $statement->bindValue('displayInSearch', 1, PDO::PARAM_INT);
        $statement->bindValue('website', $this->website->get(), PDO::PARAM_STR);

        foreach ($parameters as $parameter) {
            $statement->bindValue($parameter['identifier'], $parameter['value'], $parameter['type']);
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param integer $weekend
     *
     * @return array
     */
    private function buildExtraWithWeekend($weekend)
    {
        $query = [];

        $query['columns'] = 'tr.aanbiedingskleur AS discount_color, tr.korting_toon_als_aanbieding AS show_as_discount, tr.toonexactekorting AS show_exact_discount,
                             tr.aanbieding_acc_percentage AS discount_percentage, tr.aanbieding_acc_euro AS discount_amount, tr.voorraad_garantie,
                             tr.voorraad_allotment, tr.voorraad_optie_leverancier, tr.voorraad_xml,
                             tr.voorraad_request, tr.voorraad_optie_klant, tr.voorraad_vervallen_allotment,
                             sz.seizoen_id';

        $query['tables']  = 'tarief tr, seizoen sz';

        $query['clauses'] = ' AND (tr.seizoen_id  = sz.seizoen_id)
                              AND (tr.type_id     = t.type_id)
                              AND (sz.tonen      >= 3)
                              AND (sz.type IN (:season_type))
                              AND (tr.beschikbaar = 1)
                              AND (tr.week        = :weekend)
                              AND (
                                  tr.bruto > 0   OR
                                  tr.c_bruto > 0 OR
                                  tr.arrangementsprijs > 0
                              )';

        $query['parameters'] = [

            ['identifier' => 'season_type',
             'value'      => $this->season->get(),
             'type'       => PDO::PARAM_INT],

            ['identifier' => 'weekend',
             'value'      => $weekend,
             'type'       => PDO::PARAM_INT]
        ];

        return $query;
    }

   /**
     * @param integer $weekend
     * @param integer $persons
     *
     * @return array
     */
    private function buildExtraWithWeekendAndPersonsForAssortiments($weekend, $persons)
    {
        $query = [];

        $query['columns'] = 'tr.aanbiedingskleur AS discount_color, tr.korting_toon_als_aanbieding AS show_as_discount, tr.toonexactekorting AS show_exact_discount,
                             tr.aanbieding_acc_percentage AS discount_percentage, tr.aanbieding_acc_euro AS discount_amount, tr.voorraad_garantie,
                             tr.voorraad_allotment, tr.voorraad_optie_leverancier, tr.voorraad_xml,
                             tr.voorraad_request, tr.voorraad_optie_klant, tr.voorraad_vervallen_allotment,
                             sz.seizoen_id, tp.prijs AS price';

        $query['tables']  = 'tarief tr, tarief_personen tp, seizoen sz';

        $query['clauses'] = ' AND (a.toonper IN (1, 2))
                              AND (tr.seizoen_id  = sz.seizoen_id)
                              AND (tr.type_id     = t.type_id)
                              AND (tp.type_id = t.type_id)
                              AND (tp.seizoen_id = sz.seizoen_id)
                              AND (sz.tonen      >= 3)
                              AND (sz.type IN (:season))
                              AND (tr.beschikbaar = 1)
                              AND (tr.week        = :weekend)
                              AND (tp.week        = :weekend)
                              AND (tp.prijs       > 0)
                              AND (tp.personen    = :persons)
                              AND (
                                  tr.bruto             > 0 OR
                                  tr.c_bruto           > 0 OR
                                  tr.arrangementsprijs > 0
                              )';

        $query['parameters'] = [

            ['identifier' => 'season',
             'value'      => $this->season->get(),
             'type'       => PDO::PARAM_INT],

            ['identifier' => 'weekend',
             'value'      => $weekend,
             'type'       => PDO::PARAM_INT],

            ['identifier' => 'persons',
             'value'      => $persons,
             'type'       => PDO::PARAM_INT]
        ];

        return $query;
    }

   /**
     * @param integer $weekend
     * @param integer $persons
     *
     * @return array
     */
    private function buildExtraWithWeekendForAccommodations($weekend)
    {
        $query = [];

        $query['columns'] = 'tr.aanbiedingskleur AS discount_color, tr.korting_toon_als_aanbieding AS show_as_discount, tr.toonexactekorting AS show_exact_discount,
                             tr.aanbieding_acc_percentage AS discount_percentage, tr.aanbieding_acc_euro AS discount_amount, tr.voorraad_garantie,
                             tr.voorraad_allotment, tr.voorraad_optie_leverancier, tr.voorraad_xml,
                             tr.voorraad_request, tr.voorraad_optie_klant, tr.voorraad_vervallen_allotment,
                             sz.seizoen_id, tr.c_verkoop_site AS price';

        $query['tables']  = 'tarief tr, seizoen sz';

        $query['clauses'] = ' AND (a.toonper      = 3)
                              AND (tr.type_id     = t.type_id)
                              AND (tr.seizoen_id  = sz.seizoen_id)
                              AND (sz.tonen      >= 3)
                              AND (sz.type IN (:season))
                              AND (tr.beschikbaar = 1)
                              AND (tr.week        = :weekend)
                              AND (
                                  tr.bruto             > 0 OR
                                  tr.c_bruto           > 0 OR
                                  tr.arrangementsprijs > 0
                              )';

        $query['parameters'] = [

            ['identifier' => 'season',
             'value'      => $this->season->get(),
             'type'       => PDO::PARAM_INT],

            ['identifier' => 'weekend',
             'value'      => $weekend,
             'type'       => PDO::PARAM_INT]
        ];

        return $query;
    }

    /**
     * @param SearchBuilder $builder
     *
     * @return array
     */
    private function buildClauses(SearchBuilder $builder)
    {
        $clauseObjects = $builder->getClauses();
        $clauses       = [];

        foreach ($clauseObjects as $where) {

            if (false !== ($clause = $this->buildClause($where))) {
                $clauses[] = $clause;
            }
        }

        return $clauses;
    }

    /**
     * @param Where $where
     *
     * @return array|false
     */
    private function buildClause(Where $where)
    {
        $clause = false;

        switch ($where->getClause()) {

            case Where::WHERE_WEEKEND_SKI:

                $clause = ['sql'        => 'weekendski = :weekendski',
                           'group_id'   => 'main',
                           'parameters' => [['identifier' => 'weekendski',
                                             'value'      => $where->getValue(),
                                             'type'       => PDO::PARAM_INT]]];

                break;

            case Where::WHERE_ACCOMMODATION:

                $clause = ['sql'        => 'a.accommodatie_id IN (' . $this->integerArrayQuery($where->getValue()) . ')',
                           'group_id'   => 'accommodation_freesearch',
                           'parameters' => []];

                break;

            case Where::WHERE_COUNTRY:

                $clause = ['sql'        => $this->getLocaleField('c.seonaam') . ' IN (' . $this->stringArrayQuery($where->getValue()) . ')',
                           'group_id'   => 'region_place_country',
                           'parameters' => []];

                break;

            case Where::WHERE_REGION:

                $clause = ['sql'        => 'r.skigebied_id IN (' . $this->integerArrayQuery($where->getValue()) . ')',
                           'group_id'   => 'region_place_country',
                           'parameters' => []];

                break;

            case Where::WHERE_PLACE:

                $clause = ['sql'        => 'p.plaats_id IN (' . $this->integerArrayQuery($where->getValue()) . ')',
                           'group_id'   => 'region_place_country',
                           'parameters' => []];

                break;

            case Where::WHERE_BEDROOMS:

                $clause = ['sql'        => 't.slaapkamers >= :bedrooms',
                           'group_id'   => 'main',
                           'parameters' => [['identifier' => 'bedrooms',
                                              'value'      => $where->getValue(),
                                              'type'       => PDO::PARAM_INT]]];

                break;

            case Where::WHERE_BATHROOMS:

                $clause = ['sql'        => 't.badkamers >= :bathrooms',
                           'group_id'   => 'main',
                           'parameters' => [['identifier' => 'bathrooms',
                                             'value'      => $where->getValue(),
                                             'type'       => PDO::PARAM_INT]]];

                break;

            case Where::WHERE_PERSONS:

                if (($min = $where->getValue()) > 0) {

                    if ($persons > 40) {
                        $max = 1000;
                    } elseif ($persons >= 20) {
                        $max = 50;
                    } else {
                        $max = $this->mapMaximumPersons($min);
                    }

                    $clause = ['sql'        => 't.maxaantalpersonen >= :min_persons AND
                                                t.maxaantalpersonen <= :max_persons',

                               'group_id'   => 'main',

                               'parameters' => [['identifier' => 'min_persons',
                                                 'value'      => $min,
                                                 'type'       => PDO::PARAM_INT],

                                                ['identifier' => 'max_persons',
                                                 'value'      => $max,
                                                 'type'       => PDO::PARAM_INT]]];

                    if (WebsiteConcern::WEBSITE_ZOMERHUISJE_NL != $this->website->get()) {

                        // max 1 bedroom per person (not for Zomerhuisje.nl)
                        $clause['sql']         .= ' AND t.slaapkamers <= :bedrooms_based_on_residents';
                        $clause['parameters'][] = [

                            'identifier' => 'bedrooms_based_on_residents',
                            'value'      => $min,
                            'type'       => PDO::PARAM_INT,
                        ];
                    }
                }

                break;

            case Where::WHERE_SUPPLIER:

                $clause = ['sql'        => 't.leverancier_id IN (' . $this->stringArrayQuery($where->getValue()) . ')',
                           'group_id'   => 'main',
                           'parameters' => []];

                break;

            case Where::WHERE_FREESEARCH:

                $clause = ['sql'        => $this->getLocaleField('t.naam') . ' LIKE :freesearch_1 OR ' .
                                           $this->getLocaleField('a.naam') . ' LIKE :freesearch_2',

                           'group_id'   => 'accommodation_freesearch',

                           'parameters' => [['identifier' => 'freesearch_1',
                                             'value'      => '%' . $where->getValue() . '%',
                                             'type'       => PDO::PARAM_STR],

                                            ['identifier' => 'freesearch_2',
                                             'value'      => '%' . $where->getValue() . '%',
                                             'type'       => PDO::PARAM_STR]]];

                break;
        }

        return $clause;
    }

    /**
     * @param FilterBuilder $builder
     *
     * @return string
     */
    private function buildFilters(FilterBuilder $builder)
    {
        $filters = [];

        if (null !== ($distance = $builder->filter(FilterManager::FILTER_DISTANCE))) {
            $filters[] = $this->distance($distance);
        }

        if (null !== ($length = $builder->filter(FilterManager::FILTER_LENGTH))) {
            $filters[] = $this->length($length);
        }

        if (null !== ($facilities = $builder->filter(FilterManager::FILTER_FACILITY))) {

            $facilities = $this->facilities($facilities);

            foreach ($facilities as $facility) {
                $filters[] = $facility;
            }
        }

        if (null !== ($themes = $builder->filter(FilterManager::FILTER_THEME))) {

            $themes = $this->themes($themes);

            foreach ($themes as $theme) {
                $filters[] = $theme;
            }
        }

        return $filters;
    }

    /**
     * @param Filter $filter
     *
     * @return array|boolean
     */
    private function distance(Filter $filter)
    {
        $where = false;

        switch ($filter->getValue()) {

            case FilterManager::FILTER_DISTANCE_BY_SLOPE:

                $where = ['sql'        => 'a.afstandpiste <= :distance_by_slope',
                          'group_id'   => 'main',
                          'parameters' => [['identifier' => 'distance_by_slope',
                                            'value'      => $this->config['service']['api']['search']['distance_by_slope'],
                                            'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_DISTANCE_MAX_250:

                $where = ['sql'        => 'a.afstandpiste = :distance_max_250',
                          'group_id'   => 'main',
                          'parameters' => [['identifier' => 'distance_max_250',
                                            'value'      => 250,
                                            'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_DISTANCE_MAX_500:

                $where = ['sql'        => 'a.afstandpiste = :distance_max_500',
                          'group_id'   => 'main',
                          'parameters' => [['identifier' => 'distance_max_500',
                                            'value'      => 500,
                                            'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_DISTANCE_MAX_1000:

                $where = ['sql'        => 'a.afstandpiste = :distance_max_1000',
                          'group_id'   => 'main',
                          'parameters' => [['identifier' => 'distance_max_1000',
                                            'value'      => 1000,
                                            'type'       => PDO::PARAM_INT]]];

                break;
        }

        return $where;
    }

    /**
     * @param Filter $filter
     *
     * @return array|boolean
     */
    public function length(Filter $filter)
    {
        $where = false;

        switch ($filter->getValue()) {

            case FilterManager::FILTER_LENGTH_MAX_100:

                $where = ['sql'        => 'r.kilometerpiste < :length_max_100',
                          'group_id'   => 'main',
                          'parameters' => [['identifier' => 'length_max_100',
                                            'value'      => 100,
                                            'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_LENGTH_MIN_100:

                $where = ['sql'        => 'r.kilometerpiste >= :length_min_100',
                          'group_id'   => 'main',
                          'parameters' => [['identifier' => 'length_min_100',
                                            'value'      => 100,
                                            'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_LENGTH_MIN_200:

                $where = ['sql'        => 'r.kilometerpiste >= :length_min_200',
                          'group_id'   => 'main',
                          'parameters' => [['identifier' => 'length_min_200',
                                            'value'      => 200,
                                            'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_LENGTH_MIN_400:

                $where = ['sql'        => 'r.kilometerpiste >= :length_min_400',
                          'group_id'   => 'main',
                          'parameters' => [['identifier' => 'length_min_400',
                                            'value'      => 400,
                                            'type'       => PDO::PARAM_INT]]];

                break;
        }

        return $where;
    }

    /**
     * @param Filter $filter
     *
     * @return array
     */
    public function facilities(Filter $filter)
    {
        $clauses = [];

        foreach ($filter->getValue() as $facility) {

            if (false !== ($clause = $this->facility($facility))) {
                $clauses[] = $clause;
            }
        }

        return $clauses;
    }

    /**
     * @param integer $filter
     *
     * @return array|boolean
     */
    public function facility($facility)
    {
        $where = false;

        switch ($facility) {

            case FilterManager::FILTER_FACILITY_CATERING:

                $where = [

                    'sql'        => 'FIND_IN_SET(:facility_catering, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_catering, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'facility_catering',
                                      'value'      => 1,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_FACILITY_INTERNET_WIFI:

                $where = [

                    'sql'        => 'FIND_IN_SET(:facility_internet_wifi_1, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_internet_wifi_2, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_internet_wifi_3, a.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_internet_wifi_4, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'facility_internet_wifi_1',
                                      'value'      => 20,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_internet_wifi_2',
                                      'value'      => 22,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_internet_wifi_3',
                                      'value'      => 21,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_internet_wifi_4',
                                      'value'      => 23,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_FACILITY_SWIMMING_POOL:

                $where = [

                    'sql'        => 'FIND_IN_SET(:facility_swimming_pool_1, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_swimming_pool_2, a.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_swimming_pool_3, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'facility_swimming_pool_1',
                                      'value'      => 4,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_swimming_pool_2',
                                      'value'      => 4,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_swimming_pool_3',
                                      'value'      => 11,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_FACILITY_SAUNA:

                $where = [

                    'sql'        => 'FIND_IN_SET(:facility_sauna_1, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_sauna_2, a.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_sauna_3, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'facility_sauna_1',
                                      'value'      => 3,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_sauna_2',
                                      'value'      => 3,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_sauna_3',
                                      'value'      => 10,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_FACILITY_PRIVATE_SAUNA:

                $where = [

                    'sql'        => 'FIND_IN_SET(:facility_private_sauna_1, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_private_sauna_2, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'facility_private_sauna_1',
                                      'value'      => 3,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_private_sauna_2',
                                      'value'      => 3,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_FACILITY_PETS_ALLOWED:

                $where = [

                    'sql'        => 'FIND_IN_SET(:facility_pets_allowed_1, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_pets_allowed_2, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'facility_pets_allowed_1',
                                      'value'      => 11,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_pets_allowed_2',
                                      'value'      => 13,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_FACILITY_FIREPLACE:

                $where = [

                    'sql'        => 'FIND_IN_SET(:facility_fireplace_1, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:facility_fireplace_2, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'facility_fireplace_1',
                                      'value'      => 10,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'facility_fireplace_2',
                                      'value'      => 12,
                                      'type'       => PDO::PARAM_INT]]];

                break;
        }

        return $where;
    }

    /**
     * @param Filter $filter
     *
     * @return array
     */
    public function themes(Filter $filter)
    {
        $clauses = [];

        foreach ($filter->getValue() as $theme) {

            if (false !== ($clause = $this->theme($theme))) {
                $clauses[] = $clause;
            }
        }

        return $clauses;
    }

    /**
     * @param integer $theme
     *
     * @return array|boolean
     */
    public function theme($theme)
    {
        $where = false;

        switch ($theme) {

            case FilterManager::FILTER_THEME_KIDS:

                $where = [

                    'sql'        => 'FIND_IN_SET(:theme_kids_1, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:theme_kids_2, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'theme_kids_1',
                                      'value'      => 5,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'theme_kids_2',
                                      'value'      => 5,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_THEME_CHARMING_PLACES:

                $where = [

                    'sql'        => 'FIND_IN_SET(:theme_charming_place, p.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'theme_charming_place',
                                      'value'      => 13,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_THEME_WINTER_WELLNESS:

                $where = [

                    'sql'        => 'FIND_IN_SET(:theme_winter_wellness_1, t.kenmerken) > 0 OR
                                     FIND_IN_SET(:theme_winter_wellness_2, a.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'theme_winter_wellness_1',
                                      'value'      => 9,
                                      'type'       => PDO::PARAM_INT],

                                     ['identifier' => 'theme_winter_wellness_2',
                                      'value'      => 9,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_THEME_SUPER_SKI_STATIONS:

                $where = [

                    'sql'        => 'FIND_IN_SET(:theme_super_ski_stations, p.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'theme_super_ski_stations',
                                      'value'      => 14,
                                      'type'       => PDO::PARAM_INT]]];

                break;

            case FilterManager::FILTER_THEME_10_FOR_APRES_SKI:

                $where = [

                    'sql'        => 'FIND_IN_SET(:theme_10_for_apres_ski, p.kenmerken) > 0',

                    'group_id'   => 'main',

                    'parameters' => [['identifier' => 'theme_10_for_apres_ski',
                                      'value'      => 6,
                                      'type'       => PDO::PARAM_INT]]];

                break;
        }

        return $where;
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

    /**
     * @param integer $persons
     *
     * @return integer
     */
    private function mapMaximumPersons($persons)
    {
        return intval((isset($this->config['maximum_persons_map'][$persons]) ? $this->config['maximum_persons_map'][$persons] : $persons));
    }
}
