<?php
namespace AppBundle\Entity\Highlight;
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
    public function all($options = [])
    {
        $criteria = self::getOption($options['where'],  []);
        $order    = self::getOption($options['order'],  null);
        $limit    = self::getOption($options['limit'],  null);
        $offset   = self::getOption($options['offset'], null);
        
        return $this->findBy($criteria, $order, $limit, $offset);
    }
    
    public function find($by)
    {
        return $this->findOneBy($by);
    }
    
    public function displayable($options = [], $datetime = null)
    {
        $order  = self::getOption($options['order'],  []);
        $limit  = self::getOption($options['limit'],  null);
        $offset = self::getOption($options['offset'], null);
        
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
        
        $res = $this->matching($criteria)->toArray();
        dump($res);
        dump($limit);
        return $res;
    }
}
