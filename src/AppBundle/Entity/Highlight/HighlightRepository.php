<?php
namespace AppBundle\Entity\Highlight;

use       AppBundle\Entity\Type\Type;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Highlight\HighlightServiceRepositoryInterface;
use       Doctrine\Common\Collections\Criteria;
use       Doctrine\ORM\Mapping\ClassMetadata;

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
    public function displayable($options = [], $datetime = null)
    {
        $order    = self::getOption($options, 'order',  'rank');
        $limit    = self::getOption($options, 'limit',  null);
        $offset   = self::getOption($options, 'offset', null);
        $datetime = $datetime ?: new \DateTime('now');
        
        $qb       = $this->createQueryBuilder('h');
        $expr     = $qb->expr();
        
        $qb->select('partial h.{id, publishedAt, expiredAt}, partial t.{id, optimalResidents, maxResidents, quality}, partial a.{id, name, kind}, partial p.{id, name}, partial r.{id, name}, partial c.{id, name}')
           ->leftJoin('h.type', 't')
           ->leftJoin('t.accommodation', 'a')
           ->leftJoin('a.place', 'p')
           ->leftJoin('p.region', 'r')
           ->leftJoin('p.country', 'c')
           ->where($expr->eq('h.display', ':display'))
           ->andWhere($expr->andX(
        
               $expr->andX(
                   
                   $expr->isNotNull('h.publishedAt'),
                   $expr->lte('h.publishedAt', ':now')
               ),
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
           ->setFirstResult($offset)
           ->orderBy('h.' . $order);
           
        return $qb->getQuery()->execute();
    }
}
