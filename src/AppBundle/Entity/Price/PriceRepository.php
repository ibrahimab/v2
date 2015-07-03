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
           ->andWhere($expr->gte('pr.week', ':today'))
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
}