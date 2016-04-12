<?php
namespace AppBundle\Entity\Highlight;

use AppBundle\Concern\SeasonConcern;
use AppBundle\Concern\localeConcern;
use AppBundle\Concern\WebsiteConcern;
use AppBundle\Service\Api\Highlight\HighlightServiceRepositoryInterface;
use AppBundle\Service\Api\Search\Result\Resultset;
use Doctrine\DBAL\Connection;
use PDO;

/**
 * HighlightRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class HighlightRepository implements HighlightServiceRepositoryInterface
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var integer
     */
    protected $season;
    
    /**
     * @var string
     */
    protected $website;
    
    /**
     * @var LocaleConcern
     */
    protected $locale;

    /**
     * Constructor
     *
     * @param Connection     $db
     * @param LocaleConcern  $locale
     * @param WebsiteConcern $website
     * @param SeasonConcern  $season
     */
    public function __construct(Connection $db, LocaleConcern $locale, WebsiteConcern $website, SeasonConcern $season)
    {
        $this->db      = $db;
        $this->locale  = $locale;
        $this->website = $website;
        $this->season  = $season;
    }

    /**
     * {@InheritDoc}
     */
    public function displayable($limit, $resultsPerRow)
    {
        $order    = 'rank';
        $datetime = $datetime ?: new \DateTime('now');

        $query = "SELECT h.hoogtepunt_id AS higlight_id, h.begindatum AS published_at, h.einddatum AS expired_at, t.type_id AS type_id, t.optimaalaantalpersonen AS optimal_persons, 
                         t.maxaantalpersonen AS max_persons, t.kwaliteit AS type_quality, {$this->getLocaleField('t.naam')} AS type_name, a.accommodatie_id AS accommodation_id, 
                         a.naam AS accommodation_name, a.soortaccommodatie AS accommodation_kind, a.kwaliteit AS accommodation_quality, p.plaats_id AS place_id, p.hoortbij_plaats_id AS belongs_to_id, 
                         {$this->getLocaleField('p.naam')} AS place_name, {$this->getLocaleField('p.seonaam')} AS place_seo_name, r.skigebied_id AS region_id, {$this->getLocaleField('r.naam')} AS region_name, 
                         {$this->getLocaleField('r.seonaam')} AS region_seo_name, c.land_id AS country_id, {$this->getLocaleField('c.naam')} AS country_name, c.begincode AS country_begin_code, 
                         t.leverancier_id AS supplier_id
                  FROM   hoogtepunt h, type t, accommodatie a, plaats p, skigebied r, land c
                  WHERE  h.type_id = t.type_id
                  AND    t.accommodatie_id = a.accommodatie_id
                  AND    a.plaats_id = p.plaats_id
                  AND    p.skigebied_id = r.skigebied_id
                  AND    p.land_id = c.land_id
                  AND    h.tonen = :display
                  AND    (
                    (
                      h.begindatum IS NOT NULL OR 
                      h.begindatum <= :now
                    ) 
                    AND 
                    (
                      h.einddatum IS NULL OR 
                      h.einddatum > :now
                    )
                  ) 
                  AND FIND_IN_SET(:website, h.websites) > 0 
                  ORDER BY h.volgorde ASC LIMIT 6";

        $statement = $this->db->prepare($query);
        $statement->bindValue('now', time());
        $statement->bindValue('website', $this->website->get());
        $statement->bindValue('display', 1, PDO::PARAM_INT);
        $statement->execute();

        $results = [];
        $raw     = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($raw as $row) {

            $row['accommodation_kind'] = Resultset::getKindIdentifier($row['accommodation_kind']);
            $results[] = $row;
        }

        return $results;
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
