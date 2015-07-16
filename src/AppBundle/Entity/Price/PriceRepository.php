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
     */
    public function offers($types)
    {
        $qb   = $this->createQueryBuilder('pr');
        $expr = $qb->expr();

        $qb->select('partial pr.{id, discountActive, offerDiscountColor}')
           ->where($expr->in('pr.id', ':types'))
           ->andWhere($expr->eq('pr.discountActive', ':discountActive'))
           ->andWhere($expr->eq('pr.offerDiscountColor', ':offerDiscountColor'))
           ->andWhere($expr->gte('pr.weekend', ':today'))
           ->setParameters([

               'types'              => $types,
               'today'              => (new \DateTime())->getTimestamp(),
               'discountActive'     => true,
               'offerDiscountColor' => true,
           ]);

        $results = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        $offers  = [];

        foreach ($results as $result) {

            if (true === $result['discountActive'] && true === $result['offerDiscountColor']) {
                $offers[$result['id']] = true;
            }
        }

        return $offers;
    }

    public function availableTypes($weekend)
    {
        $qb    = $this->createQueryBuilder('pr');
        $expr  = $qb->expr();
        $bruto = $expr->orX()
                      ->add($expr->gt('pr.bruto', 0))
                      ->add($expr->gt('pr.cbruto', 0))
                      ->add($expr->gt('pr.arrangementPrice', 0));

        $qb->select('partial pr.{id, discountActive, offerDiscountColor}')
           ->where($expr->eq('pr.weekend', ':weekend'))
           ->andWhere($expr->eq('pr.available', ':available'))
           ->andWhere($bruto)
           ->setParameters([

               'weekend'   => $weekend,
               'available' => true,
           ]);

        $results = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        $types   = [];

        foreach ($results as $result) {

            $types[$result['id']] = [

                'id'    => $result['id'],
                'offer' => (true === $result['discountActive'] && true === $result['offerDiscountColor']),
            ];
        }

        return $types;
    }

    public function pricesWithWeekendAndPersons($weekend, $persons)
    {
        $qb    = $this->getEntityManager()->createQueryBuilder();
        $expr  = $qb->expr();
        $bruto = $expr->orX()
                      ->add($expr->gt('pr.bruto', 0))
                      ->add($expr->gt('pr.cbruto', 0))
                      ->add($expr->gt('pr.arrangementPrice', 0));

        $qb->select('pr.id, pr.discountActive, pr.offerDiscountColor, prp.price')
           ->add('from', 'AppBundle:Price\Price pr, AppBundle:Season\Season s, AppBundle:Price\Person prp')
           ->where('pr.id = prp.id')
           ->andWhere('pr.seasonId = s.id')
           ->andWhere('pr.weekend = prp.weekend')
           ->andWhere('prp.seasonId = s.id')
           ->andWhere($expr->eq('pr.weekend', ':weekend'))
           ->andWhere($expr->eq('pr.available', ':available'))
           ->andWhere($expr->gt('prp.price', 0))
           ->andWhere($expr->eq('prp.persons', ':persons'))
           ->andWhere($expr->gt('pr.weekend', ':today'))
           ->andWhere($expr->gte('s.display', ':display'))
           ->andWhere($expr->eq('s.season', ':season'))
           ->andWhere($bruto)
           ->setParameters([

               'weekend'   => $weekend,
               'available' => true,
               'persons'   => $persons,
               'today'     => (new \DateTime())->getTimestamp(),
               'display'   => 3,
               'season'    => $this->getSeason(),
           ]);

        $results = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        $prices  = [];

        foreach ($results as $result) {

            $prices[$result['id']] = [

                'id'    => $result['id'],
                'offer' => (true === $result['discountActive'] && true === $result['offerDiscountColor']),
                'price' => $result['price'],
            ];
        }

        return $prices;
    }
}