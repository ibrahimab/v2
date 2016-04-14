<?php
namespace AppBundle\Service\Api\PricesAndOffers\Repository;

use AppBundle\Concern\WebsiteConcern;
use AppBundle\Concern\SeasonConcern;
use Doctrine\DBAL\Connection;
use PDO;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class PriceRepository implements PriceRepositoryInterface
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var WebsiteConcern
     */
    private $website;

    /**
     * @var SeasonConcern
     */
    private $season;

    /**
     * Constructor
     *
     * @param Connection     $db
     * @param WebsiteConcern $website
     * @param SeasonConcern  $season
     */
    public function __construct(Connection $db, WebsiteConcern $website, SeasonConcern $season)
    {
        $this->db      = $db;
        $this->website = $website;
        $this->season  = $season;
    }

    /**
     * @param integer    $weekend
     * @param array|null $typeIds
     *
     * @return array
     */
    public function getPricesByWeekend($weekend, $typeIds = null)
    {
        $query = 'SELECT tp.type_id, MIN(tp.prijs) AS price
                  FROM   tarief tr, tarief_personen tp, seizoen sz
                  WHERE  tr.seizoen_id  = sz.seizoen_id AND tp.type_id = tr.type_id AND tp.seizoen_id = sz.seizoen_id
                  AND    tp.week = tr.week AND tr.week > :now AND sz.tonen >= 3
                  AND    sz.type IN (:season) AND tr.beschikbaar = 1 AND (
                      tr.bruto             > 0 OR
                      tr.c_bruto           > 0 OR
                      tr.arrangementsprijs > 0
                  )
                  AND tp.prijs > 0 AND tr.week = :weekend';

        if (is_array($typeIds)) {
            $query .= ' AND tp.type_id IN (' . implode(', ', $typeIds) . ')';
        }

        $query .= ' GROUP BY tp.type_id';

        $now       = time();
        $statement = $this->db->prepare($query);
        $statement->bindValue('now', $now);
        $statement->bindValue('weekend', $weekend);
        $statement->bindValue('season', $this->season->get());
        $statement->execute();

        $arrangements = $statement->fetchAll(PDO::FETCH_ASSOC);
        $arrangements = array_column($arrangements, 'price', 'type_id');

        $query = 'SELECT tr.type_id, MIN(tr.c_verkoop_site) AS price
                  FROM   accommodatie a, type t, tarief tr, seizoen sz
                  WHERE  a.toonper = 3 AND tr.type_id = t.type_id AND tr.seizoen_id = sz.seizoen_id
                  AND    tr.week > :now AND sz.tonen >= 3 AND sz.type IN (:season)
                  AND    tr.beschikbaar = 1 AND (tr.bruto > 0 OR tr.c_bruto > 0 OR tr.arrangementsprijs > 0)
                  AND    t.accommodatie_id = a.accommodatie_id AND FIND_IN_SET(:website, t.websites) > 0
                  AND    a.tonen = 1 AND a.tonenzoekformulier = 1 AND t.tonen = 1 AND t.tonenzoekformulier = 1
                  AND    tr.week = :weekend';

        if (is_array($typeIds)) {
            $query .= ' AND tr.type_id IN (' . implode(', ', $typeIds) . ')';
        }

        $query .= ' GROUP BY tr.type_id';

        $statement = $this->db->prepare($query);
        $statement->bindValue('now', $now);
        $statement->bindValue('weekend', $weekend);
        $statement->bindValue('season', $this->season->get());
        $statement->bindValue('website', $this->website->get());
        $statement->execute();

        $accommodations = $statement->fetchAll(PDO::FETCH_ASSOC);
        $accommodations = array_column($accommodations, 'price', 'type_id');

        return $this->mergeResults($arrangements, $accommodations);
    }

    /**
     * @param integer    $persons
     * @param array|null $typeIds
     *
     * @return array
     */
    public function getPricesByPersons($persons, $typeIds = null)
    {
        $query = 'SELECT tp.type_id, MIN(tp.prijs) AS price
                  FROM   tarief tr, tarief_personen tp, seizoen sz
                  WHERE  tr.seizoen_id = sz.seizoen_id AND tp.type_id = tr.type_id AND tp.seizoen_id = sz.seizoen_id
                  AND    tp.week = tr.week AND tr.week > :now AND sz.tonen >= 3 AND sz.type IN (:season) AND tr.beschikbaar = 1
                  AND    (
                      tr.bruto             > 0 OR
                      tr.c_bruto           > 0 OR
                      tr.arrangementsprijs > 0
                  )
                  AND tp.prijs > 0 AND tp.personen = :persons';

        if (is_array($typeIds)) {
            $query .= ' AND tp.type_id IN (' . implode(', ', $typeIds) . ')';
        }
        $query .= ' GROUP BY tp.type_id';

        $now       = time();
        $statement = $this->db->prepare($query);
        $statement->bindValue('now', $now);
        $statement->bindValue('season', $this->season->get());
        $statement->bindValue('persons', $persons);
        $statement->execute();

        $arrangements = $statement->fetchAll(PDO::FETCH_ASSOC);
        $arrangements = array_column($arrangements, 'price', 'type_id');

        $query = 'SELECT tr.type_id, MIN(tr.c_verkoop_site) AS price
                  FROM   accommodatie a, type t, tarief tr, seizoen sz
                  WHERE  a.toonper = 3 AND tr.type_id = t.type_id AND tr.seizoen_id = sz.seizoen_id AND tr.week > :now
                  AND    sz.tonen >= 3 AND sz.type IN (:season) AND tr.beschikbaar = 1
                  AND    (
                      tr.bruto             > 0 OR
                      tr.c_bruto           > 0 OR
                      tr.arrangementsprijs > 0
                  ) AND t.accommodatie_id = a.accommodatie_id
                  AND FIND_IN_SET(:website, t.websites) > 0 AND a.tonen = 1 AND a.tonenzoekformulier = 1
                  AND t.tonen = 1 AND t.tonenzoekformulier = 1';

        if (is_array($typeIds)) {
            $query .= ' AND tr.type_id IN (' . implode(', ', $typeIds) . ')';
        }

        $query .= ' GROUP BY tr.type_id';

        $statement = $this->db->prepare($query);
        $statement->bindValue('now', $now);
        $statement->bindValue('season', $this->season->get());
        $statement->bindValue('website', $this->website->get());

        $accommodations = $statement->fetchAll(PDO::FETCH_ASSOC);
        $accommodations = array_column($accommodations, 'price', 'type_id');

        return $this->mergeResults($arrangements, $accommodations);
    }

    /**
     * @param integer    $weekend
     * @param integer    $persons
     * @param array|null $typeIds
     *
     * @return array
     */
    public function getPricesByWeekendAndPersons($weekend, $persons, $typeIds = null)
    {
        $query = 'SELECT tp.type_id, MIN(tp.prijs) AS price
                  FROM   tarief tr, tarief_personen tp, seizoen sz
                  WHERE  tr.seizoen_id = sz.seizoen_id AND tp.type_id = tr.type_id AND tp.seizoen_id = sz.seizoen_id
                  AND    tp.week = tr.week AND tr.week > :now AND sz.tonen >= 3 AND sz.type IN (:season) AND tr.beschikbaar = 1
                  AND    (
                      tr.bruto             > 0 OR
                      tr.c_bruto           > 0 OR
                      tr.arrangementsprijs > 0
                  )
                  AND tp.prijs > 0 AND tp.personen = :persons AND tr.week = :weekend';

        if (is_array($typeIds)) {
            $query .= ' AND tp.type_id IN (' . implode(', ', $typeIds) . ')';
        }

        $query .= ' GROUP BY tp.type_id';

        $now       = time();
        $statement = $this->db->prepare($query);
        $statement->bindValue('now', $now);
        $statement->bindValue('season', $this->season->get());
        $statement->bindValue('weekend', $weekend);
        $statement->bindValue('persons', $persons);
        $statement->execute();

        $arrangements = $statement->fetchAll(PDO::FETCH_ASSOC);
        $arrangements = array_column($arrangements, 'price', 'type_id');

        $query = 'SELECT tr.type_id, MIN(tr.c_verkoop_site) AS price
                  FROM   accommodatie a, type t, tarief tr, seizoen sz
                  WHERE  a.toonper = 3 AND tr.type_id = t.type_id AND tr.seizoen_id = sz.seizoen_id AND tr.week > :now
                  AND    sz.tonen >= 3 AND sz.type IN (:season) AND tr.beschikbaar = 1
                  AND    (
                      tr.bruto             > 0 OR
                      tr.c_bruto           > 0 OR
                      tr.arrangementsprijs > 0
                  ) AND t.accommodatie_id = a.accommodatie_id
                  AND FIND_IN_SET(:website, t.websites) > 0 AND a.tonen = 1 AND a.tonenzoekformulier = 1
                  AND t.tonen = 1 AND t.tonenzoekformulier = 1 AND tr.week = :weekend';

        if (is_array($typeIds)) {
            $query .= ' AND tr.type_id IN (' . implode(', ', $typeIds) . ')';
        }

        $query .= ' GROUP BY tr.type_id';

        $statement = $this->db->prepare($query);
        $statement->bindValue('now', $now);
        $statement->bindValue('season', $this->season->get());
        $statement->bindValue('website', $this->website->get());
        $statement->bindValue('weekend', $weekend);

        $accommodations = $statement->fetchAll(PDO::FETCH_ASSOC);
        $accommodations = array_column($accommodations, 'price', 'type_id');

        return $this->mergeResults($arrangements, $accommodations);
    }

    /**
     * @param array $arrangements
     * @param array $accommodations
     *
     * @return array
     */
    private function mergeResults($arrangements, $accommodations)
    {
        $results = [];

        foreach ($arrangements as $typeId => $price) {
            $results[intval($typeId)] = floatval($price);
        }

        foreach ($accommodations as $typeId => $price) {
            $results[intval($typeId)] = floatval($price);
        }

        return $results;
    }
}