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
     * @var array
     */
    private $cache;

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
        $this->cache          = [];
    }

    /**
     * @param  integer      $typeId
     * @param  integer|null $weekend
     * @param  integer|null $persons
     * @param  array        $options
     *
     * @return array
     */
    public function get($typeId, $weekend = null, $persons = null)
    {
        if (isset($this->cache[$typeId . '-' . $weekend . '-' . $persons])) {
            return $this->cache[$typeId];
        }

        $connection  = $this->connection;
        $qb          = $connection->createQueryBuilder();
        $expr        = $qb->expr();
        $locale      = $this->localeConcern->get();
        $localeField = $this->localeField;
        $personText  = $this->translator->trans('person');
        $personsText = $this->translator->trans('persons');
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

        $result                       = $statement->fetch();
        $result['show']               = (int)$result['show'];
        $result['name']               = $result['name_accommodation'] . ($result['name_type'] ? (' ' . $result['name_type']) : '');
        $result['accommodation_kind'] = $this->translator->trans('type.kind.' . $result['accommodation_kind']);
        $result['name_accommodation'] = $result['accommodation_kind'] . ' ' . $result['name'];
        $result['persons']            = ((int)$result['optimal_persons'] === 1 ? $personText : $personsText);
        $result['number_of_persons']  = $result['optimal_persons'] . ($result['optimal_persons'] <> $result['max_persons'] ? (' - ' . $result['max_persons']) : '') . ' ' . $result['persons'];
        $result['name_persons']       = $result['name'] . ' (' . $result['optimal_persons'] . ($result['optimal_persons'] <> $result['max_persons'] ? (' - ' . $result['max_persons']) : '') . ' ' . $this->translator->trans('persons') . ')';

        if ($result['name_accommodation'] <> $result['order_name']) {
            $result['order_name'] = $result['order_name'] . ($result['name_type'] ? (' ' . $result['name_type']) : '');
        }

        $result['url'] = $this->router->generate('show_type_' . $locale, [

            'beginCode' => $result['begincode'],
            'typeId'    => $result['type_id'],

        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $result['place_url'] = $this->router->generate('show_place_' . $locale, [

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
            $result['number_of_persons_list'][$i] = $i . ' ' . ($i === 1 ? $personText : $personsText);
        }

        $result['departure_day_adjustments'] = $this->getDepartureDayAdjustments($result['type_id'], $result['accommodatie_id']);

        if ($result['show'] === 3) {
            $result['arrival_dates'] = $this->getAccommodationArrivalDates($result['type_id'], $result['arrival_plus_min'], $result['departure_day_adjustments']);
        } else {
            $result['arrival_dates']  = $this->getArrangementArrivalDates($result['type_id'], $result['arrival_plus_min'], $result['departure_day_adjustments']);
        }

        $result['departure'] = null;

        if (null !== $weekend) {

            $arrival = new \DateTime();
            $arrival->setTimestamp($weekend);

            $result['arrival']   = $weekend;
            $result['departure'] = $this->getDepartureDay($arrival, $result['arrival_dates']['weekends'], $result['arrival_plus_min'], $result['departure_plus_min']);
        }

        if ($result['show'] <> 3 || false === $resale) {
            $result['skipas'] = $this->getSkiPas($result['accommodation_id']);
        } else {
            $result['skipas'] = null;
        }

        if (null !== $weekend && null !== $persons) {

            if ($result['show'] === 3 || $resale) {

                $data                 = $this->calculateAccommodationPrice($result['type_id'], $weekend, $persons);
                $result['price']      = $data['price'];
                $result['season_id']  = $data['season_id'];
                $result['insurances'] = $data['insurances'];

            } else {

                $data                 = $this->calculateArrangementPrice($result['type_id'], $weekend, $persons);
                $result['price']      = $data['price'];
                $result['season_id']  = $data['season_id'];
                $result['insurances'] = $data['insurances'];
            }

            $offers = $this->getOffers($result['type_id'], $result['season_id'], $weekend);
            $price  = $result['price'];

            if (null !== $offers) {

                $currentPrice = $result['price'];
                $price        = $this->processOffer($result['price'], $offers, $weekend);

                if ($currentPrice > $price) {
                    $result['offer'] = true;
                }
            }

            $result['price'] = $price;

            if ($result['show'] === 3 || $resale) {

                $result['accommodation_price'] = $result['price'];

            } else {

                if (null !== $offers && isset($offers['type'][$typeId]['prices'][$weekend])) {
                    $accommodationPrice = $this->processOffer($result['arrival_dates']['price_per_week'][$weekend], $offers['type'][$typeId]['prices'], $weekend);
                } else {
                    $accommodationPrice = $result['arrival_dates']['price_per_week'][$weekend];
                }

                $result['accommodation_price'] = round($accommodationPrice, 2);
            }
        }

        return $result;
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
        $localeField = $this->localeField;

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

    /**
     * @param  integer $typeId
     * @param  integer $weekend
     * @param  integer $persons
     *
     * @return array|null
     */
    public function calculateAccommodationPrice($typeId, $weekend, $persons)
    {
        $connection = $this->connection;
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        $resale     = $this->websiteConcern->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE);

        $statement  = $qb->select('t.c_verkoop_site AS sale_site, t.wederverkoop_verkoopprijs AS resale_saleprice, t.wederverkoop_commissie_agent AS resale_commision_agent, s.seizoen_id AS season_id,
                                   s.annuleringsverzekering_poliskosten AS cancellation_insurance_policy_fee, s.annuleringsverzekering_percentage_1 AS cancellation_insurance_percentage_1, s.annuleringsverzekering_percentage_2 AS cancellation_insurance_percentage_2,
                                   s.annuleringsverzekering_percentage_3 AS cancellation_insurance_percentage_3, s.annuleringsverzekering_percentage_4 AS cancellation_insurance_percentage_4, s.schadeverzekering_percentage AS damage_insurance_percentage,
                                   s.reisverzekering_poliskosten AS travel_insurance_policy_fee, s.verzekeringen_poliskosten AS insurances_policy_fee')
                         ->from('tarief t, seizoen s', '')
                         ->andWhere($expr->eq('t.type_id', ':typeId'))
                         ->andWhere('t.seizoen_id = s.seizoen_id')
                         ->andWhere($expr->eq('t.week', ':weekend'))
                         ->setParameter('typeId', $typeId)
                         ->setParameter('weekend', $weekend)
                         ->execute();

        $result     = $statement->fetch();
        $data       = [];

        if (false !== $result) {

            $data['price']      = (true === $resale ? $result['resale_saleprice'] : $result['sale_site']);
            $data['season_id']  = $result['season_id'];
            $data['insurances'] = [

                'cancellation_insurance_policy_fee' => $result['cancellation_insurance_policy_fee'],
                'cancellation_percentages'          => [

                    1 => $result['cancellation_insurance_percentage_1'],
                    2 => $result['cancellation_insurance_percentage_2'],
                    3 => $result['cancellation_insurance_percentage_3'],
                    4 => $result['cancellation_insurance_percentage_4'],
                ],
                'damage_insurance_percentage' => $result['damage_insurance_percentage'],
                'insurances_policy_fee'       => $result['insurances_policy_fee'],
                'commission'                  => (true === $resale ? $result['resale_commision_agent'] : null),
                'travel_insurance_policy_fee' => $result['travel_insurance_policy_fee'],
            ];
        }

        return (false !== $result ? $data : null);
    }

    /**
     * @param  integer $typeId
     * @param  integer $weekend
     * @param  integer $persons
     *
     * @return array|null
     */
    public function calculateArrangementPrice($typeId, $weekend, $persons)
    {
        $connection = $this->connection;
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();

        $statement  = $qb->select('tp.prijs AS price, s.seizoen_id AS season_id, s.annuleringsverzekering_poliskosten AS cancellation_insurance_policy_fee, s.annuleringsverzekering_percentage_1 AS cancellation_insurance_percentage_1,
                                   s.annuleringsverzekering_percentage_2 AS cancellation_insurance_percentage_2, s.annuleringsverzekering_percentage_3 AS cancellation_insurance_percentage_3, s.annuleringsverzekering_percentage_4 AS cancellation_insurance_percentage_4,
                                   schadeverzekering_percentage AS damage_insurance_percentage, s.reisverzekering_poliskosten AS travel_insurance_policy_fee, s.verzekeringen_poliskosten AS insurances_policy_fee')
                         ->from('tarief_personen tp, seizoen s', '')
                         ->andWhere($expr->eq('tp.type_id', ':typeId'))
                         ->andWhere('tp.seizoen_id = s.seizoen_id')
                         ->andWhere($expr->eq('tp.week', ':weekend'))
                         ->andWhere($expr->eq('personen', ':persons'))
                         ->setParameter('typeId', $typeId)
                         ->setPArameter('weekend', $weekend)
                         ->setParameter('persons', $persons)
                         ->execute();

        $result     = $statement->fetch();
        $data       = [];

        if (false !== $result) {

            $data['price']      = $result['price'];
            $data['season_id']  = $result['season_id'];
            $data['insurances'] = [

                'cancellation_insurance_policy_fee' => $result['cancellation_insurance_policy_fee'],
                'cancellation_percentages'          => [

                    1 => $result['cancellation_insurance_percentage_1'],
                    2 => $result['cancellation_insurance_percentage_2'],
                    3 => $result['cancellation_insurance_percentage_3'],
                    4 => $result['cancellation_insurance_percentage_4'],
                ],
                'damage_insurance_percentage' => $result['damage_insurance_percentage'],
                'insurances_policy_fee'       => $result['insurances_policy_fee'],
                'travel_insurance_policy_fee' => $result['travel_insurance_policy_fee'],
            ];
        }

        return (false !== $result ? $data : null);
    }

    public function getOffers($typeId, $seasonId, $weekend = null)
    {
        $connection  = $this->connection;
        $qb          = $connection->createQueryBuilder();
        $expr        = $qb->expr();
        $personText  = $this->translator->trans('person');
        $personsText = $this->translator->trans('persons');
        $localeField = $this->localeField;
        $now         = time();
        $threshold   = ($now + (86400 * 21));
        $results     = [];
        $offerKinds  = [

            'accommodations' => ['table' => 'aanbieding_accommodatie aa', 'where' => 'aa.aanbieding_id = a.aanbieding_id AND aa.accommodatie_id = ac.accommodatie_id'],
            'types'          => ['table' => 'aanbieding_type at',         'where' => 'at.aanbieding_id = a.aanbieding_id AND at.type_id         = t.type_id']];

        $whereSeason = $expr->orX(

            $expr->eq('a.seizoen1_id', ':seasonId'),
            $expr->eq('a.seizoen2_id', ':seasonId'),
            $expr->eq('a.seizoen3_id', ':seasonId')
        );

        foreach ($offerKinds as $kind => $data) {

            $query = $qb->select('a.aanbieding_id AS offer_id, a.naam, a.onlinenaam' . $localeField . ' AS online_name, a.omschrijving' . $localeField . ' AS description, a.tonen AS display,
                                  a.archief AS archive, a.soort AS kind, a.toon_abpagina AS show_ab_page, a.bedrag AS price, a.bedrag_soort AS price_kind, UNIX_TIMESTAMP(a.begindatum) AS start_date,
                                  UNIX_TIMESTAMP(a.einddatum) AS end_date, a.toonkorting AS show_discount, a.toon_als_aanbieding AS show_as_offer, ad.week, t.type_id, ac.naam AS accommodation_name, ac.tonen AS show_accommodation,
                                  t.tonen AS show_type, t.websites AS websites, ac.soortaccommodatie AS kind_accommodation, t.naam' . $localeField . ' AS name_type, t.optimaalaantalpersonen AS optimal_persons,
                                  t.maxaantalpersonen AS max_persons, p.naam' . $localeField . ' AS name_place, s.naam AS name_region, l.naam' . $localeField . ' AS name_country, l.begincode')
                        ->from('aanbieding a, accommodatie ac, aanbieding_aankomstdatum ad, type t, tarief ta, plaats p, land l, skigebied s, aanbieding_accommodatie aa ', '')
                        ->andWhere('ta.week = ad.week')
                        ->andWhere('ta.type_id = t.type_id')
                        ->andWhere($expr->eq('ta.beschikbaar', ':available'))
                        ->andWhere('ac.plaats_id = p.plaats_id')
                        ->andWhere('p.land_id = l.land_id')
                        ->andWhere('p.skigebied_id = s.skigebied_id')
                        ->andWhere('ad.aanbieding_id = a.aanbieding_id')
                        ->andWhere('t.accommodatie_id = ac.accommodatie_id')
                        ->andWhere($whereSeason)
                        ->andWhere($expr->eq('t.type_id', ':typeId'))
                        ->andWhere('aa.aanbieding_id = a.aanbieding_id')
                        ->andWhere('aa.accommodatie_id = ac.accommodatie_id')
                        ->orderBy('a.soort, a.onlinenaam' . $localeField . ', a.begindatum, ac.naam, t.optimaalaantalpersonen, t.maxaantalpersonen, ad.week, t.naam')
                        ->setParameter('available', 1)
                        ->setParameter('seasonId', $seasonId)
                        ->setParameter('typeId', $typeId);

            if (null !== $weekend) {

                $query->andWhere($expr->eq('ta.week', ':weekend'))
                      ->setParameter('weekend', $weekend);
            }

            $statement = $query->execute();
            $rows      = $statement->fetchAll();

            foreach ($rows as $row) {

                if (!isset($results[$row['offer_id']])) {

                    $results['offer'][$row['offer_id']] = [

                        'id'            => (int)$row['offer_id'],
                        'name'          => $row['online_name'],
                        'description'   => $row['description'],
                        'kind_id'       => (int)$row['kind_id'],
                        'kind'          => (int)$row['kind'],
                        'show_ab_page'  => $row['show_ab_page'],
                        'price'         => (float)$row['price'],
                        'price_kind'    => (int)$row['price_kind'],
                        'start_date'    => (int)$row['start_date'],
                        'end_date'      => (int)$row['end_date'],
                        'arrival_date'  => (int)$row['week'],
                        'show_discount' => (int)$row['show_discount'],
                        'show_as_offer' => (int)$row['show_as_offer'],
                        'show'          => (int)$row['show'],
                        'archive'       => $row['archive'],
                        'weeks'         => [],
                        'accommodation' => [],
                    ];
                }

                if (null !== $typeId) {
                    $results['offer'][$row['offer_id']]['weeks'][$row['week']] = $row['price'];
                } else {
                    $results['offer'][$row['offer_id']]['accommodation'][$row['type_id']][$row['week']] = $row['price'];
                }

                if ($row['kind'] === 2 && $row['week'] > $now) {

                    if (!isset($results[$row['offer_id']]['last_minute'])) {
                        $results['offer'][$row['offer_id']]['last_minute'] = true;
                    }

                    if ($row['week'] > $threshold) {
                        $results['offer'][$row['offer_id']]['last_minute'] = false;
                    }
                }

                if (!isset($results['type'][$row['type_id']])) {

                    $kind                             = $this->translator->trans('type.kind.' . $row['accommodation_kind']);
                    $results['type'][$row['type_id']] = [

                        'kind_accommodation' => $kind,
                        'name_accommodation' => ucfirst($kind) . ' ' . $row['name_accommodation'] . ($row['name_type'] ? (' ' . $row['name_type']) : ''),
                        'accommodation_id'   => (int)$row['accommodation_id'],
                        'type_id'            => (int)$row['type_id'],
                        'name_place'         => $row['name_place'],
                        'name_region'        => $row['name_region'],
                        'name_country'       => $row['name_country'],
                        'url'                => $this->router->generate('show_type_' . $locale, [

                            'beginCode' => $row['begincode'],
                            'typeId'    => $result['type_id'],

                        ], UrlGeneratorInterface::ABSOLUTE_URL),
                        'show'               => (int)$row['show'],
                        'optimal_persons'    => (int)$row['optimal_persons'],
                        'max_persons'        => (int)$row['max_persons'],
                        'persons'            => ($row['optimal_persons'] . ($row['max_persons'] > $row['optimal_persons'] ? (' - ' . $row['max_persons']) : '') . ' ' . ((int)$row['max_persons'] === 1 ? $personText : $personsText)),
                        'display'            => ((int)$row['display_accommodation'] === 1 && (int)$row['display_type'] === 1),
                        'websites'           => explode(',', $row['websites']),
                        'show_discount'      => ((int)$row['show_discount'] === 1),
                        'show_as_offer'      => ((int)$row['show_as_offer'] === 1),
                        'prices'             => [],
                    ];
                }

                if (!isset($results['type'][$row['type_id']]['prices'][$row['week']])) {
                    $results['type'][$row['type_id']]['prices'][$row['week']] = 0;
                }

                $results['type'][$row['type_id']]['prices'][$row['week']] += $row['price'];

                switch ((int)$row['price_kind']) {

                    case 1:

                        if (!isset($results['type'][$row['type_id']]['discount_euro'][$row['week']])) {
                            $results['type'][$row['type_id']]['discount_euro'][$row['week']] = 0;
                        }

                        $results['type'][$row['type_id']]['discount_euro'][$row['week']] += $row['price'];

                        break;

                    case 2:

                        if (!isset($results['type'][$row['type_id']]['discount_percentage'][$row['week']])) {
                            $results['type'][$row['type_id']]['discount_percentage'][$row['week']] = 0;
                        }

                        $results['type'][$row['type_id']]['discount_percentage'][$row['week']] += $row['price'];

                        break;

                    case 3:

                        if (!isset($results['type'][$row['type_id']]['exact_price'][$row['week']])) {
                            $results['type'][$row['type_id']]['exact_price'][$row['week']] = 0;
                        }

                        $results['type'][$row['type_id']]['exact_price'][$row['week']] += $row['price'];

                        break;
                }
            }
        }

        return $results;
    }

    /**
     * @param  float   $price
     * @param  array   $offers
     * @param  integer $weekend
     *
     * @return float
     */
    public function processOffer($price, $offers, $weekend)
    {
        if ($price > 0) {

            if (isset($offers['exact_price']) && $offers['exact_price'][$weekend] > 0) {
                $price = $offers['exact_price'][$weekend];
            }

            if (isset($offers['discount_percentage']) && $offers['discount_percentage'][$weekend] > 0) {
                $price = floor(($price * (1 - $offers['discount_percentage'][$weekend] / 100)) / 5) * 5;
            }

            if (isset($offers['discount_euro']) && $offers['discount_euro'][$weekend] > 0) {
                $price = $price - $offers['discount_euro'][$weekend];
            }
        }

        return ($price > 0 ? $price : null);
    }
}