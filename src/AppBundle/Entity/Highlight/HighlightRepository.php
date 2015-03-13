<?php
namespace AppBundle\Entity\Highlight;
use       AppBundle\Entity\Type\Type;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Highlight\HighlightServiceRepositoryInterface;
use       Doctrine\Common\Collections\Criteria;

/**
 * HighlightRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class HighlightRepository extends BaseRepository implements HighlightServiceRepositoryInterface
{   
    /**
     * {@InheritDoc}
     */
    public function displayable_deoptimized($options = [], $datetime = null)
    {
        $order  = self::getOption($options, 'order',  []);
        $limit  = self::getOption($options, 'limit',  null);
        $offset = self::getOption($options, 'offset', null);
        
        $datetime          = $datetime ?: new \DateTime('now');
        $expression        = Criteria::expr();
        $criteria          = Criteria::create();
        $criteria->where($expression->eq('display', true))
                 ->where($expression->andX(
                    $expression->lte('publishedAt', $datetime),
                    $expression->orX(
                        $expression->isNull('expiredAt'),
                        $expression->gt('expiredAt', $datetime)
                    )
                 ))
                 ->setMaxResults($limit)
                 ->setFirstResult($offset)
                 ->orderBy($order);
        
        return $this->matching($criteria)->toArray();
    }
    
    public function displayable($options = [], $datetime = null)
    {
        $order    = self::getOption($options, 'order',  null);
        $limit    = self::getOption($options, 'limit',  null);
        $offset   = self::getOption($options, 'offset', null);
        $datetime = $datetime ?: new \DateTime('now');
        
        $qb       = $this->createQueryBuilder('h', '*');
        $expr     = $qb->expr();        
        $qb->innerJoin('h.type', 't')
           ->innerJoin('t.accommodation', 'a')
           ->innerJoin('a.place', 'p')
           ->innerJoin('p.region', 'r')
           ->where($expr->eq('h.display', ':display'))
           ->andWhere($expr->andX(
        
               $expr->lte('h.publishedAt', ':now'),
               $expr->orX(
               
                   $expr->isNull('h.expiredAt'),
                   $expr->gt('h.expiredAt', ':now')
               )
           ))
           ->setParameters([
               
               'display' => true,
               'now'     => $datetime,
           ])
           ->setMaxResults($limit)
           ->setFirstResult($offset);
                       
        if (null !== $order) {
            $qb->orderBy($order);
        }
        
        return $qb->getQuery()->getResult();
    }
}
