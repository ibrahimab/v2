<?php
namespace AppBundle\Entity\Booking\Survey;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Region\RegionServiceEntityInterface;
use       AppBundle\Service\Api\Country\CountryServiceEntityInterface;
use       AppBundle\Service\Api\Booking\Survey\SurveyServiceRepositoryInterface;
use       Doctrine\ORM\EntityRepository;

/**
 * SurveyRepository
 */
class SurveyRepository extends BaseRepository implements SurveyServiceRepositoryInterface
{   
    /**
     * {@InheritDoc}
     */
    public function statsByType(TypeServiceEntityInterface $type)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $query        = $queryBuilder->select('s.typeId, COUNT(s.id) AS surveyCount, AVG(s.question_1_7) AS surveyAverageOverallRating')
                                     ->where($queryBuilder->expr()->eq('s.typeId', ':typeId'))
                                     ->setParameter('typeId', $type->getId())
                                     ->getQuery();
        
        return $query->getArrayResult();
    }
    
    /**
     * {@InheritDoc}
     */
    public function statsByTypes($types)
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $query        = $queryBuilder->select('s.typeId, COUNT(s.id) AS surveyCount, AVG(s.question_1_7) AS surveyAverageOverallRating')
                                     ->where($queryBuilder->expr()->in('s.type', ':types'))
                                     ->setParameter('types', $types)
                                     ->groupBy('s.type')
                                     ->getQuery();
        
        return $query->getArrayResult();
    }

    /**
     * {@InheritDoc}
     */
    public function statsByRegion(RegionServiceEntityInterface $region)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('COUNT(s.id) AS surveyCount, AVG(s.question_1_7) AS surveyAverageOverallRating')
                    ->innerJoin('s.type', 't')
                    ->innerJoin('t.accommodation', 'a')
                    ->innerJoin('a.place', 'p')
                    ->where($expr->eq('p.region', ':region'))
                    ->andWhere($expr->gt('s.question_1_7', ':question_1_7'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([
                        
                        'region'       => $region,
                        'question_1_7' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => 0,
                    ])
                    ->getQuery();
        
        return $query->getSingleResult();
    }

    /**
     * {@InheritDoc}
     */
    public function statsByCountry(CountryServiceEntityInterface $country)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('COUNT(s.id) AS surveyCount, AVG(s.question_1_7) AS surveyAverageOverallRating')
                    ->innerJoin('s.type', 't')
                    ->innerJoin('t.accommodation', 'a')
                    ->innerJoin('a.place', 'p')
                    ->where($expr->eq('p.country', ':country'))
                    ->andWhere($expr->gt('s.question_1_7', ':question_1_7'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([
                        
                        'country'      => $country,
                        'question_1_7' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => 0,
                    ])
                    ->getQuery();
        
        return $query->getSingleResult();
    }
}
