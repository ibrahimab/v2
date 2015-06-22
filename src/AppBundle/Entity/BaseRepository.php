<?php
namespace AppBundle\Entity;

use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       Doctrine\ORM\Query\Expr;
use       Doctrine\ORM\EntityRepository;

/**
 * BaseRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class BaseRepository extends EntityRepository
{
    use BaseRepositoryTrait;
    /**
     * {@InheritDoc}
     */
    public function all($options = [])
    {
        $criteria = self::getOption($options, 'where',  []);
        $order    = self::getOption($options, 'order',  null);
        $limit    = self::getOption($options, 'limit',  null);
        $offset   = self::getOption($options, 'offset', null);

        return $this->findBy($criteria, $order, $limit, $offset);
    }

    /**
     * {@InheritDoc}
     */
    public function find($by = [])
    {
        return $this->findOneBy($by);
    }

    /**
     * Published expression for QueryBuilder instances
     *
     * @param string $fieldPrefix
     * @param Expr $expr
     * @return Expr
     */
    public function publishedExpr($fieldPrefix, $expr)
    {
        return $expr->andX(

            $expr->andX(

                $expr->isNotNull($fieldPrefix . '.publishedAt'),
                $expr->lte($fieldPrefix . '.publishedAt', ':now')
            ),
            $expr->orX(

                $expr->isNull($fieldPrefix . '.expiredAt'),
                $expr->gt($fieldPrefix . '.expiredAt', ':now')
            )
        );
    }
}