<?php
namespace AppBundle\Entity\Season;
use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Season\SeasonServiceRepositoryInterface;
use       Doctrine\ORM\NoResultException;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class SeasonRepository extends BaseRepository implements SeasonServiceRepositoryInterface
{
    /**
     * @const float
     */
    const DEFAULT_INSURANCES_POLICY_COSTS = 3.75;

    /**
     * @return int
     */
    public function getInsurancesPolicyCosts()
    {
        $qb   = $this->createQueryBuilder('s');
        $expr = $qb->expr();

        $qb->select('s.insurancesPolicyCosts')
           ->where($expr->gt('s.insurancesPolicyCosts', ':insurancesPolicyCosts'))
           ->andWhere($expr->eq('s.season', ':season'))
           ->andWhere('UNIX_TIMESTAMP(s.end) > :end')
           ->orderBy('s.start')
           ->setMaxResults(1)
           ->setParameters([

               'insurancesPolicyCosts' => 0,
               'season'                => $this->getSeason(),
               'end'                   => (new \DateTime())->getTimestamp(),
           ]);

        try {

            $costs = $qb->getQuery()->getSingleScalarResult();

        } catch (NoResultException $exception) {
            $costs = self::DEFAULT_INSURANCES_POLICY_COSTS;
        }

        return $costs;
    }
}