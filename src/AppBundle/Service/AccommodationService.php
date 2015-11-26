<?php
namespace AppBundle\Service;

use       AppBundle\Concern\LocaleConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Service\UtilsService;
use       Doctrine\DBAL\Connection;
use       Symfony\Component\Translation\TranslatorInterface;
use       Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class AccommodationService
{
    const DEFAULT_LOCALE = 'nl';

    /**
     * @var LocaleConcern
     */
    private $localeConcern;

    /**
     * @var string
     */
    private $localeField;

    /**
     * @var WebsiteConcern
     */
    private $websiteConcern;

    /**
     * @var SeasonConcern
     */
    private $seasonConcern;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * Constructor
     *
     * @param  LocaleConcern  $localeConcern
     * @param  WebsiteConcern $websiteConcern
     * @param  SeasonConcern  $seasonConcern
     * @param  Connection     $connection
     */
    public function __construct(LocaleConcern $localeConcern, WebsiteConcern $websiteConcern, SeasonConcern $seasonConcern, Connection $connection, TranslatorInterface $translator, UrlGeneratorInterface $router)
    {
        $this->localeConcern  = $localeConcern;
        $this->localeField    = ($this->localeConcern->get() === self::DEFAULT_LOCALE ? '' : ('_' . $this->localeConcern->get()));
        $this->websiteConcern = $websiteConcern;
        $this->seasonConcern  = $seasonConcern;
        $this->connection     = $connection;
        $this->translator     = $translator;
        $this->router         = $router;
    }

    /**
     * @param  integer      $typeId
     * @param  integer|null $weekend
     * @param  integer|null $persons
     * @param  array        $options
     *
     * @return array
     */
    public function get($typeId, $weekend = null, $persons = null, $options = [])
    {
        $connection  = $this->connection;
        $qb          = $connection->createQueryBuilder();
        $expr        = $qb->expr();
        $locale      = $this->localeConcern->get();
        $localeField = $this->localeField;
        $person      = $this->translator->trans('person');
        $persons     = $this->translator->trans('persons');
        $resale      = $this->websiteConcern->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE);

        $statement   = $qb->select('a.wzt as season, a.naam AS name_accommodation, a.bestelnaam AS order_name,
                                    a.soortaccommodatie as accommodation_kind, a.toonper AS `show`, a.flexibel AS flexible,
                                    t.websites, a.vertrekinfo_seizoengoedgekeurd AS departure_info_season_approved, a.vertrekinfo_seizoengoedgekeurd_en AS departure_info_season_approved_en,
                                    a.accommodatie_id AS accommodation_id, t.leverancier_id AS supplier_id, a.aankomst_plusmin AS arrival_plus_min, a.vertrek_plusmin AS departure_plus_min, a.receptie' . $localeField . ' AS reception,
                                    a.telefoonnummer AS phone, a.voucherinfo' . $localeField . ' AS voucherinfo_accommodation, t.voucherinfo' . $localeField . ' AS voucherinfo_type, a.mailtekst_id AS mail_text_id, a.optiedagen_klanten_vorig_seizoen AS option_days_clients_last_season, a.korteomschrijving' . $localeField . ' AS short_description_accommodation,
                                    t.korteomschrijving' . $localeField . ' AS short_description_type, a.inclusief' . $localeField . ' AS including_accommodation, t.inclusief' . $localeField . ' AS including_type, a.exclusief' . $localeField . ' AS exclusive_accommodation, t.exclusief' . $localeField . ' AS exclusive_type,
                                    a.tonen AS display_accommodation, t.tonen AS display_type, t.type_id, t.naam' . $localeField . ' AS name_type, t.code, t.optimaalaantalpersonen AS optimal_persons, t.maxaantalpersonen AS max_persons, t.slaapkamers AS bedrooms, t.slaapkamersextra' . $localeField . ' AS bedrooms_extra, t.badkamers AS bathrooms, t.badkamersextra' . $localeField . ' AS bathrooms_extra,
                                    t.oppervlakte AS surface, t.oppervlakteextra' . $localeField . ' AS surface_extra, t.zomerwinterkoppeling_accommodatie_id AS summer_winter_connection_accommodation_id, a.kwaliteit AS quality_accommodation, t.kwaliteit AS quality_type, t.aangepaste_min_tonen AS adjusted_min_display, t.leverancierscode AS supplier_code, t.beheerder_id AS manager_id, t.eigenaar_id AS owner_id,
                                    t.verzameltype AS compilation_type, t.verzameltype_parent AS compilation_parent_type, t.voorraad_gekoppeld_type_id AS supplier_connection_type_id, p.naam' . $localeField . ' AS name_place, p.plaats_id AS place_id, l.naam' . $localeField . ' AS name_country, l.begincode, s.naam AS name_region, lev.aflopen_allotment AS supplier_allotment')
                         ->from('type t, accommodatie a, plaats p, land l, leverancier lev, skigebied s', '')
                         ->andWhere('p.skigebied_id = s.skigebied_id')
                         ->andWhere('t.leverancier_id = lev.leverancier_id')
                         ->andWhere($expr->eq('t.type_id', ':type_id'))
                         ->andWhere('t.accommodatie_id = a.accommodatie_id')
                         ->andWhere('a.plaats_id = p.plaats_id')
                         ->andWhere('p.land_id = l.land_id')
                         ->setParameter('type_id', $typeId)
                         ->execute();

        $result = $statement->fetch();

        $result['show']               = (int)$result['show'];
        $result['name']               = $result['name_accommodation'] . ($result['name_type'] ? (' ' . $result['name_type']) : '');
        $result['accommodation_kind'] = $this->translator->trans('type.kind.' . $result['accommodation_kind']);
        $result['name_accommodation'] = $result['accommodation_kind'] . ' ' . $result['name'];
        $result['persons']            = ((int)$result['optimal_persons'] === 1 ? $person : $persons);
        $result['number_of_persons']  = $result['optimal_persons'] . ($result['optimal_persons'] <> $result['max_persons'] ? (' - ' . $result['max_persons']) : '') . ' ' . $result['persons'];
        $result['name_persons']       = $result['name'] . ' (' . $result['optimal_persons'] . ($result['optimal_persons'] <> $result['max_persons'] ? (' - ' . $result['max_persons']) : '') . ' ' . $this->translator->trans('persons') . ')';

        if ($result['name_accommodation'] <> $result['order_name']) {
            $result['order_name'] = $result['order_name'] . ($result['name_type'] ? (' ' . $result['name_type']) : '');
        }

        $result['url'] = $this->router->generate('show_type_' . $locale, [

            'beginCode' => $result['begincode'],
            'typeId'    => $result['type_id'],

        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $result['plaats_url'] = $this->router->generate('show_place_' . $locale, [

            'placeSlug' => UtilsService::seo($result['name_place']),

        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $websitesConfig     = $this->websiteConcern->websites();
        $result['websites'] = explode(',', $result['websites']);
        $result['resale']   = false;

        foreach ($websitesConfig as $website => $websiteConfig) {

            if (true === $websiteConfig['resale'] && in_array($website, $result['websites'])) {
                $result['resale'] = true;
            }
        }

        $result['quality']                = ($result['quality_type'] ? $result['quality_type'] : $result['quality_accommodation']);
        $result['display']                = ($result['display_type'] && $result['display_accommodation']);
        $result['short_description']      = ($result['short_description_type'] ? $result['short_description_type'] : ($result['short_description_accommodation'] ? $result['short_description_accommodation'] : ''));
        $result['display_least']          = ($result['optimal_persons'] > 12 ? ($result['adjusted_min_display'] ? $result['adjusted_min_display'] : (floor($result['optimal_persons'] * 0.5))) : 1);
        $result['number_of_persons_list'] = [];

        $start = 1;
        if (true === $result['resale'] || $result['show'] === 3) {
            $start = $result['display_least'];
        }

        for ($i = $start; $i <= $result['max_persons']; $i++) {
            $result['number_of_persons_list'][$i] = $i . ' ' . ($i === 1 ? $person : $persons);
        }

        $result['departure_day_adjustments'] = $this->getDepartureDayAdjustments($result['type_id'], $result['accommodatie_id']);

        if ($result['show'] === 3) {
            $result['arrival_dates'] = $this->getAccommodationArrivalDates($result['type_id'], $result['arrival_plus_min'], $result['departure_day_adjustments']);
        } else {
            $result['arrival_dates']  = $this->getArrangementArrivalDates($result['type'], $result['arrival_plus_min'], $result['departure_day_adjustments']);
        }

        $result['departure'] = null;

        if (null !== $weekend) {

            $arrival = new \DateTime();
            $arrival->setTimestamp($weekend);

            $result['departure'] = $this->getDepartureDay($arrival, $result['arrival_dates']['weekends'], $result['arrival_plus_min'], $result['departure_plus_min']);
        }

        if ($result['show'] <> 3 || false === $resale) {
            $result['skipas'] = $this->getSkiPas($result['accommodation_id']);
        }

        dump($result);exit;
    }

    /**
     * @param  integer $typeId
     * @param  integer $accommodationId
     *
     * @return array
     */
    public function getDepartureDayAdjustments($typeId, $accommodationId)
    {
        $connection  = $this->connection;
        $qb          = $connection->createQueryBuilder();
        $expr        = $qb->expr();

        $statement   = $qb->select('v.naam AS name, v.toelichting' . $localeField . ' AS description, v.vertrekdagtype_id AS departure_day_type_id,
                                    v.soort AS kind, v.afwijking AS deviation, UNIX_TIMESTAMP(s.begin) AS start, UNIX_TIMESTAMP(s.eind) AS end, asz.seizoen_id AS season_id')
                          ->from('accommodatie a, vertrekdagtype v, type t, seizoen s, accommodatie_seizoen asz', '')
                          ->andWhere('a.accommodatie_id = asz.accommodatie_id')
                          ->andWhere('a.accommodatie_id = t.accommodatie_id')
                          ->andWhere($expr->eq('t.type_id', ':type_id'))
                          ->andWhere('asz.vertrekdagtype_id = v.vertrekdagtype_id')
                          ->andWhere('asz.seizoen_id = s.seizoen_id')
                          ->setParameter('type_id', $typeId)
                          ->execute();

        $results      = $statement->fetchAll();
        $adjustments  = [];
        $endDate      = new \DateTime();
        $weekDate     = new \DateTime();
        $weekInterval = new \DateInterval('P7D');

        foreach ($results as $result) {

            $result['season_id'] = (int)$result['season_id'];
            $result['kind']      = (int)$result['kind'];
            $result['start']     = (int)$result['start'];
            $result['end']       = (int)$result['end'];

            $adjustments[$result['season_id']]['name']        = $result['name'];
            $adjustments[$result['season_id']]['description'] = $result['description'];
            $adjustments[$result['season_id']]['id']          = $result['departure_day_type_id'];

            if ($result['kind'] === 2) {

                $week = $weekDate->setTimestamp($result['start']);
                $end  = $endDate->setTimestamp($result['end'])
                                ->add($weekInterval)
                                ->getTimestamp();

                while ($week <= $end) {

                    $adjustments[$result['season_id']][(int)$weekDate->format('dm')] = 1;
                    $week = $weekDate->add($weekInterval)
                                     ->getTimestamp();
                }

            } elseif ($result['kind'] === 1 && $result['deviation']) {

                $data = explode("\n", $result['deviation']);

                foreach ($data as $key => $value) {

                    if (1 === preg_match('/(?P<key>[0-9]{4}) (?P<value>.[0-9])$/i', $value, $matches)) {
                        $adjustments[$result['season_id']][$matches['key']] = (int)$matches['value'];
                    }
                }
            }
        }

        return $adjustments;
    }

    /**
     * @param  integer $typeId
     * @param  integer $arrivalPlusMin
     * @param  array   $departureDayAdjustments
     *
     * @return array
     */
    public function getAccommodationArrivalDates($typeId, $arrivalPlusMin, $departureDayAdjustments)
    {
        $connection = $this->connection;
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();

        $statement  = $qb->select('t.week, s.tonen AS display, t.c_bruto AS gross, t.c_verkoop_site AS site_sale, t.beschikbaar AS available,
                                   t.blokkeren_wederverkoop AS block_resale, t.wederverkoop_verkoopprijs AS resale_saleprice, s.seizoen_id AS season_id')
                         ->from('tarief t, seizoen s', '')
                         ->andWhere('t.seizoen_id = s.seizoen_id')
                         ->andWhere($expr->eq('t.type_id', ':type_id'))
                         ->setParameter('type_id', $typeId)
                         ->execute();

        $results    = $statement->fetchAll();
        $weekDate   = new \DateTime();
        $resale     = $this->websiteConcern->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE);
        $arrivals   = ['weekends' => [], 'available' => [], 'known_price' => []];

        foreach ($results as $result) {

            $result['week']         = (int)$result['week'];
            $time                   = $result['week'];
            $result['season_id']    = (int)$result['season_id'];
            $result['display']      = (int)$result['display'];
            $result['available']    = (int)$result['available'];
            $result['block_resale'] = (int)$result['block_resale'];

            $weekDate->setTimestamp($result['week']);

            if (isset($departureDayAdjustments[$result['season_id']][$weekDate->format('dm')]) || $arrivalPlusMin) {

                $interval = new \DateInterval('P' . ($departureDayAdjustments[$result['season_id']][$weekDate->format('dm')] + $arrivalPlusMin) . 'D');
                $time     = $weekDate->setTimestamp($result['week'])
                                     ->add($interval)
                                     ->getTimestamp();
            }

            $arrivals['weekends'][$result['week']] = $time;

            if ($result['display'] > 1 && $result['gross'] > 0 && $result['site_sale'] && $result['available'] === 1 && ($result['block_resale'] === 0 || !$resale) && ($result['resale_saleprice'] > 0 || !$resale)) {
                $arrivals['available'][$result['week']] = $time;
            }

            if ($result['site_sale'] > 0 && ($result['resale_saleprice'] > 0 || !$resale)) {
                $arrivals['known_price'][$result['week']] = $time;
            }
        }

        return $arrivals;
    }

    /**
     * @param  integer $typeId
     *
     * @return array
     */
    public function getArrangementArrivalDates($typeId, $arrivalPlusMin, $departureDayAdjustments)
    {
        $connection = $this->connection;
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();

        $statement  = $qb->select('t.week, s.tonen AS display, t.bruto AS gross, t.arrangementsprijs AS arrangement_price, t.beschikbaar AS available,
                                   blokkeren_wederverkoop AS block_resale, t.wederverkoop_verkoopprijs AS resale_saleprice, s.seizoen_id AS season_id')
                         ->from('tarief t, seizoen s', '')
                         ->andWhere('t.seizoen_id = s.seizoen_id')
                         ->andWhere($expr->eq('t.type_id', ':type_id'))
                         ->setParameter('type_id', $typeId)
                         ->execute();

        $results    = $statement->fetchAll();
        $weekDate   = new \DateTime();
        $resale     = $this->websiteConcern->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE);
        $arrivals   = ['weekends' => [], 'available' => [], 'known_price' => []];

        foreach ($results as $result) {

            $result['week']      = (int)$result['week'];
            $time                = $result['week'];
            $result['season_id'] = (int)$result['season_id'];
            $result['display']   = (int)$result['display'];
            $result['available'] = (int)$result['available'];

            $weekDate->setTimestamp($result['week']);

            if (isset($departureDayAdjustments[$result['season_id']][$weekDate->format('dm')]) || $arrivalPlusMin) {

                $interval = new \DateInterval('P' . ($departureDayAdjustments[$result['season_id']][$weekDate->format('dm')] + $arrivalPlusMin) . 'D');
                $time     = $weekDate->setTimestamp($result['week'])
                                     ->add($interval)
                                     ->getTimestamp();
            }

            $arrivals['weekends'][$result['week']] = $time;

            if ($result['display'] > 1 && ($result['gross'] > 0 || $result['arrangement_price'] > 0) && $result['available'] === 1 && ($result['block_resale'] === 0 || !$resale)) {
                $arrivals['available'][$result['week']] = $time;
            }

            if (($result['gross'] > 0 || $result['arrangement_price']) && ($result['resale_saleprice']) > 0 || !$resale) {
                $arrivals['date_prices'][$result['week']] = $week;
            }

            if ($result['resale_saleprice'] > 0) {

                $arrivals['price_per_week'][$result['week']] = (($result['resale_saleprice'] / 100) * $result['gross']);
                $arrivals['weekend_price'][$result['week']] = floor(($result['price_per_week']) / 5) * 5;
            }
        }

        return $arrivals;
    }

    /**
     * @param  \DateTime $arrival
     * @param  array     $arrivals
     * @param  integer   $arrivalPlusMin
     * @param  integer   $departurePlusMin
     *
     * @return \DateTime
     */
    public function getDepartureDay(\DateTime $arrival, $arrivals, $arrivalPlusMin, $departurePlusMin)
    {
        $end       = $arrival->add(new \DateInterval('P7D'));
        $timestamp = $arrival->getTimestamp();
        $departure = null;

        if (isset($arrivals[$timestamp])) {

            $departure = $arrivals[$timestamp];

        } elseif (!isset($arrivals[$timestamp]) && $arrivalPlusMin <> 0 && $arrivalPlusMin === $departurePlusMin) {

            // sunday - sunday trips
            $departure = $end->add(new \DateInterval('P' . $departurePlusMin . 'D'));

        } else {

            $departure = $timestamp;
        }

        if ($departurePlusMin <> 0){
            $departure = $end->sub('P' . ($departurePlusMin + $arrivalPlusMin) . 'D');
        }

        return $departure;
    }

    /**
     * @param  integer $accommodationId
     *
     * @return array|boolean
     */
    public function getSkiPas($accommodationId)
    {
        $connection = $this->connection;
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();

        $statement  = $qb->select('s.skipas_id, s.naam AS name, s.naam_voorkant AS name_front, s.aantaldagen AS days')
                         ->from('skipas s, accommodatie a', '')
                         ->andWhere('a.skipas_id = s.skipas_id')
                         ->andWhere($expr->eq('a.accommodatie_id', ':accommodationId'))
                         ->setParameter('accommodationId', $accommodationId)
                         ->execute();

        $skipas     = $statement->fetch();

        return (false === $skipas ? null : $skipas);
    }
}