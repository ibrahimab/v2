<?php
namespace AppBundle\Entity\HomepageBlock;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\HomepageBlock\HomepageBlockServiceRepositoryInterface;

/**
 * HomepageBlockRepository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
class HomepageBlockRepository extends BaseRepository implements HomepageBlockServiceRepositoryInterface
{
    /**
     * Finding published blocks
     *
     * @param array $options
     * @return HomepageBlockServiceEntityInterface[]
     */
    public function published($options = [])
    {
        $by       = self::getOption($options, 'where', []);
        $limit    = self::getOption($options, 'limit', 3);

        $qb       = $this->createQueryBuilder('h');
        $expr     = $qb->expr();
        $datetime = new \DateTime('now');
        $parameters = [

            'display' => true,
            'now'     => $datetime->format('Y-m-d'),
            'website' => $this->getWebsite(),
        ];

        $qb->andwhere($this->publishedExpr('h', $expr))
           ->andWhere($this->activeWebsiteExpr('h', $expr))
           ->andWhere($expr->eq('h.display', ':display'));

        foreach ($by as $field => $value) {

            $qb->andWhere('h.' . $field, ':' . $field);
            $parameters[$field] = $value;
        }

        $qb->setMaxResults($limit);
        $qb->setParameters($parameters);

        return $qb->getQuery()->getResult();
    }
}