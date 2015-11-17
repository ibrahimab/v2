<?php
namespace AppBundle\Service;

use       AppBundle\Concern\LocaleConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Concern\SeasonConcern;
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
        $accommodation = $this->getType($typeId);
    }

    /**
     * @param  integer $typeId
     *
     * @return array
     */
    public function getType($typeId)
    {
        $connection  = $this->connection;
        $qb          = $connection->createQueryBuilder();
        $expr        = $qb->expr();
        $locale      = $this->localeConcern->get();
        $localeField = ($locale === self::DEFAULT_LOCALE ? '' : ('_' . $locale));

        $statement  = $qb->select('a.wzt as season, a.naam AS name_accommodation, a.bestelnaam AS order_name,
                                   a.soortaccommodatie as accommodation_kind, a.toonper AS `show`, a.flexibel AS flexible,
                                   t.websites, a.vertrekinfo_seizoengoedgekeurd AS departure_info_season_approved, a.vertrekinfo_seizoengoedgekeurd_en AS departure_info_season_approved_en,
                                   a.accommodatie_id AS accommodation_id, t.leverancier_id AS supplier_id, a.aankomst_plusmin AS arrival_plusmin, a.vertrek_plusmin AS departure_plus_min, a.receptie' . $localeField . ' AS reception,
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

        $result['name']               = $result['name_accommodation'] . ($result['name_type'] ? (' ' . $result['name_type']) : '');
        $result['accommodation_kind'] = $this->translator->trans('type.kind.' . $result['accommodation_kind']);
        $result['name_accommodation'] = $result['accommodation_kind'] . ' ' . $result['name'];
        $result['persons']            = ((int)$result['optimal_persons'] === 1 ? $this->translator->trans('person') : $this->translator->trans('persons'));
        $result['number_of_persons']  = $result['optimal_persons'] . ($result['optimal_persons'] <> $result['max_persons'] ? (' - ' . $result['max_persons']) : '') . ' ' . $result['persons'];
        $result['name_persons']       = $result['name'] . ' (' . $result['optimal_persons'] . ($result['optimal_persons'] <> $result['max_persons'] ? (' - ' . $result['max_persons']) : '') . ' ' . $this->translator->trans('persons') . ')';

        if ($result['name_accommodation'] <> $result['order_name']) {
            $result['order_name'] = $result['order_name'] . ($result['name_type'] ? (' ' . $result['name_type']) : '');
        }

        $result['url'] = $this->router->generate('show_type_' . $locale, [

            'beginCode' => $result['begincode'],
            'typeId'    => $result['type_id'],

        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $result['plaats_url'] = $this->router->generate('');
    }
}