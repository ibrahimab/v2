<?php
namespace AppBundle\Entity\Price;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Price\PriceServiceRepositoryInterface;
use       Doctrine\ORM\Query;

/**
 * PriceRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @version 0.0.5
 * @since   0.0.5
 * @package Chalet
 */
class PriceRepository extends BaseRepository implements PriceServiceRepositoryInterface
{
    /**
     * @param array $types
     * @return array
     * @deprecated
     */
    public function offers($types)
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();

        $qb->select('`type_id` AS `id`, `kortingactief` AS `discountActive`, `aanbiedingskleur_korting` AS `offerDiscountColor`')
           ->from('tarief', 't')
           ->where($expr->in('type_id', $types))
           ->andWhere('kortingactief = 1')
           ->andWhere('aanbiedingskleur_korting = 1')
           ->andWhere($expr->gt('week', (new \DateTime())->getTimestamp()));

        $statement = $qb->execute();
        $results = [];
        $results   = $statement->fetchAll();
        $offers    = [];

        foreach ($results as $result) {

            $result = array_map('intval', $result);

            if (1 === $result['discountActive'] && 1 === $result['offerDiscountColor']) {
                $offers[$result['id']] = true;
            }
        }

        return $offers;
    }

    /**
     * @param integer $weekend
     * @return array
     */
    public function getArrangementDataBy($weekend = null, $persons = null)
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        $bruto      = $expr->orX()
                           ->add($expr->gt('tr.bruto', 0))
                           ->add($expr->gt('tr.c_bruto', 0))
                           ->add($expr->gt('tr.arrangementsprijs', 0));

        $qb->select('tr.type_id, MIN(tp.prijs) AS prijs, tr.kortingactief, tr.aanbiedingskleur_korting')
           ->from('tarief tr, tarief_personen tp, seizoen s', '')
           ->where('tr.seizoen_id = s.seizoen_id')
           ->andWhere('tp.type_id = tr.type_id')
           ->andWhere('tp.seizoen_id = s.seizoen_id')
           ->andWhere('tp.week = tr.week')
           ->andWhere($expr->gt('tr.week', ':today'))
           ->andWhere($expr->gte('s.tonen', ':show'))
           ->andWhere($expr->eq('s.type', ':season'))
           ->andWhere($expr->eq('tr.beschikbaar', ':available'))
           ->andWhere($bruto)
           ->groupBy('tp.type_id')
           ->setParameters([

               'today'     => time(),
               'show'      => 3,
               'season'    => $this->getSeason(),
               'available' => 1,
           ]);

        if (null !== $weekend) {

            $qb->andWhere($expr->eq('tr.week', ':weekend'))
               ->setParameter('weekend', $weekend);
        }

        if (null !== $persons) {

            $qb->andWhere($expr->eq('tp.personen', ':persons'))
               ->setParameter('persons', $persons);
        }

        $statement = $qb->execute();
        $results   = $statement->fetchAll();
        $data      = [];

        foreach ($results as $result) {

            $data[] = [

                'id'    => $result['type_id'],
                'offer' => (1 === (int)$result['kortingactief'] && 1 === (int)$result['aanbiedingskleur_korting']),
                'price' => floatval($result['prijs']),
            ];
        }

        return $data;
    }

    /**
     * @param integer $weekend
     * @return array
     */
    public function getArrangementDataByWeekend($weekend)
    {
        return $this->getArrangementDataBy($weekend);
    }

    /**
     * @param integer $weekend
     * @return array
     */
    public function getAccommodationDataByWeekend($weekend)
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        $bruto      = $expr->orX()
                           ->add($expr->gt('tr.bruto', 0))
                           ->add($expr->gt('tr.c_bruto', 0))
                           ->add($expr->gt('tr.arrangementsprijs', 0));

        $qb->select('tr.type_id, MIN(tr.c_verkoop_site) AS prijs, tr.kortingactief, tr.aanbiedingskleur_korting')
           ->from('accommodatie a, type t, tarief tr, seizoen s', '')
           ->where($expr->eq('a.toonper', ':kind'))
           ->andWhere('tr.seizoen_id = s.seizoen_id')
           ->andWhere('tr.type_id = t.type_id')
           ->andWhere('tr.seizoen_id = s.seizoen_id')
           ->andWhere('t.accommodatie_id = a.accommodatie_id')
           ->andWhere($expr->gt('FIND_IN_SET(:website, t.websites)', 0))
           ->andWhere($expr->eq('a.tonen', 1))
           ->andWhere($expr->eq('a.tonenzoekformulier', 1))
           ->andWhere($expr->eq('t.tonen', 1))
           ->andWhere($expr->eq('t.tonenzoekformulier', 1))
           ->andWhere($expr->gt('tr.week', ':today'))
           ->andWhere($expr->gte('s.tonen', ':show'))
           ->andWhere($expr->eq('s.type', ':season'))
           ->andWhere($expr->eq('tr.beschikbaar', ':available'))
           ->andWhere($bruto)
           ->andWhere($expr->eq('tr.week', ':weekend'))
           ->groupBy('tr.type_id')
           ->setParameters([

               'kind'      => 3,
               'today'     => time(),
               'show'      => 3,
               'season'    => $this->getSeason(),
               'website'   => $this->getWebsite(),
               'weekend'   => $weekend,
               'available' => 1,
           ]);

        $statement = $qb->execute();
        $results   = $statement->fetchAll();
        $data      = [];

        foreach ($results as $result) {

            $data[] = [
                
                'id'            => $result['type_id'],
                'offer'         => (1 === (int)$result['kortingactief'] && 1 === (int)$result['aanbiedingskleur_korting']),
                'price'         => floatval($result['prijs']),
                'accommodation' => true,
            ];
        }

        return $data;
    }

    /**
     * @param integer $weekend
     * @return array
     */
    public function getDataByWeekend($weekend)
    {
        $arrangements   = $this->getArrangementDataByWeekend($weekend);
        $accommodations = $this->getAccommodationDataByWeekend($weekend);

        return array_merge($arrangements, $accommodations);
    }

    /**
     * @param integer $persons
     * @return array
     */
    public function getArrangementDataByPersons($persons)
    {
        return $this->getArrangementDataBy(null, $persons);
    }

    /**
     * @param integer $persons
     * @return array
     */
    public function getAccommodationDataByPersons($persons)
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        $bruto      = $expr->orX()
                           ->add($expr->gt('tr.bruto', 0))
                           ->add($expr->gt('tr.c_bruto', 0))
                           ->add($expr->gt('tr.arrangementsprijs', 0));

        $qb->select('tr.type_id, tr.c_verkoop_site AS prijs, tr.week, tr.kortingactief, tr.aanbiedingskleur_korting')
           ->from('accommodatie a, type t, tarief tr, seizoen s', '')
           ->where('tr.type_id = t.type_id')
           ->andWhere('tr.seizoen_id = s.seizoen_id')
           ->andWhere('t.accommodatie_id = a.accommodatie_id')
           ->andWhere($expr->gt('tr.week', ':today'))
           ->andWhere($expr->gte('s.tonen', ':show'))
           ->andWhere($expr->eq('s.type', ':season'))
           ->andWhere($expr->eq('tr.beschikbaar', ':available'))
           ->andWhere($bruto)
           ->andWhere($expr->eq('a.toonper', ':kind'))
           ->andWhere($expr->gt('FIND_IN_SET(:website, t.websites)', 0))
           ->andWhere($expr->eq('a.tonen', 1))
           ->andWhere($expr->eq('a.tonenzoekformulier', 1))
           ->andWhere($expr->eq('t.tonen', 1))
           ->andWhere($expr->eq('t.tonenzoekformulier', 1))
           ->setParameters([

               'kind'      => 3,
               'today'     => time(),
               'show'      => 3,
               'season'    => $this->getSeason(),
               'website'   => $this->getWebsite(),
               'available' => 1,
           ]);

        $statement = $qb->execute();
        $results   = $statement->fetchAll();
        $data      = [];
        $prices    = [];

        foreach ($results as $result) {

            if (!isset($data[$result['type_id']])) {

                $data[$result['type_id']] = [
                
                    'id'            => $result['type_id'],
                    'offer'         => (1 === (int)$result['kortingactief'] && 1 === (int)$result['aanbiedingskleur_korting']),
                    'prices'        => [],
                    'accommodation' => true,
                ];
            }

            if ($result['prijs'] > 0) {
                $data[$result['type_id']]['prices'][(int)$result['week']] = floatval($result['prijs']);
            }
        }

        return $data;
    }

    /**
     * @param integer $persons
     * @return array
     */
    public function getDataByPersons($persons)
    {
        $arrangements   = $this->getArrangementDataByPersons($persons);
        $accommodations = $this->getAccommodationDataByPersons($persons);

        return array_merge($arrangements, $accommodations);
    }

    public function getArrangementDataByWeekendAndPersons($weekend, $persons)
    {
        return $this->getArrangementDataBy($weekend, $persons);
    }

    public function getDataByWeekendAndPersons($weekend, $persons)
    {
        $arrangements   = $this->getArrangementDataByWeekendAndPersons($weekend, $persons);
        $accommodations = $this->getAccommodationDataByWeekend($weekend);

        return array_merge($arrangements, $accommodations);
    }
    
    public function getDataByWeekendAndPersons($weekend, $persons)
    {
        $arrangements   = $this->getArrangementDataBy($weekend, $persons);
        $accommodations = $this->getAccommodationDataByPersons($persons);
        
        return array_merge($arrangements, $accommodations);
    }
}
