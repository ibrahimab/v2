<?php
namespace AppBundle\Service;

use       AppBundle\Concern\LocaleConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Concern\SeasonConcern;

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

    public function __construct(LocaleConcern $localeConcern, WebsiteConcern $websiteConcern, SeasonConcern $seasonConcern)
    {
        $this->localeConcern  = $localeConcern;
        $this->websiteConcern = $websiteConcern;
        $this->seasonConcern  = $seasonConcern;
    }

    public function get($typeId, $weekend = null, $persons = null, $options = [])
    {
        $accommodation = $this->getType($typeId);
    }

    public function getType($typeId)
    {
        $connection  = $this->getEntityManager()->getConnection();
        $qb          = $connection->createQueryBuilder();
        $locale      = $this->localeConcern->get();
        $localeField = ($locale === self::DEFAULT_LOCALE ? '' : '_') . $locale;

        $statement  = $qb->select('a.wzt as season, a.naam AS name, a.bestelnaam AS order_name,
                                   a.soortaccommodatie as accommodation_kind, a.toonper AS show, a.flexibel AS flexible
                                   t.websites, a.vertrekinfo_seizoengoedgekeurd AS departure_info_season_approved, a.vertrekinfo_seizoengoedgekeurd_en AS departure_info_season_approved_en,
                                   a.accommodatie_id AS id, t.leverancier_id AS supplier_id, a.aankomst_plusmin AS arrival_plusmin, a.vertrek_plus_min AS departure_plus_min, a.receptie' . $localeField . ' AS reception,
                                   ');
    }
}