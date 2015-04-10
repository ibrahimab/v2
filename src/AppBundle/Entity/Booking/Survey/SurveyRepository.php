<?php
namespace AppBundle\Entity\Booking\Survey;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       AppBundle\Service\Api\Place\PlaceServiceEntityInterface;
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
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('COUNT(s.id) AS surveyCount, AVG(s.question_1_7) AS surveyAverageOverallRating')
                    ->leftJoin('s.type', 't')
                    ->leftJoin('t.accommodation', 'a')
                    ->where($expr->eq('t', ':type'))
                    ->andWhere($expr->gt('s.question_1_7', ':question_1_7'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([
        
                        'type'         => $type,
                        'question_1_7' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => false,
                    ])
                    ->getQuery();
        
        return $query->getSingleResult();
    }
    
    /**
     * {@InheritDoc}
     */
    public function statsByTypes($types)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('s.typeId, COUNT(s.id) AS surveyCount, AVG(s.question_1_7) AS surveyAverageOverallRating')
                    ->leftJoin('s.type', 't')
                    ->leftJoin('t.accommodation', 'a')
                    ->where($qb->expr()->in('s.type', ':types'))
                    ->andWhere($expr->gt('s.question_1_7', ':question_1_7'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([
                    
                        'types'        => $types,
                        'question_1_7' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => false,
                    ])
                    ->groupBy('s.type')
                    ->getQuery();

        return $query->getArrayResult();
    }

    /**
     * {@InheritDoc}
     */
    public function statsByPlace(PlaceServiceEntityInterface $place)
    {
        $qb    = $this->createQueryBuilder('s');
        $expr  = $qb->expr();
        $query = $qb->select('t.id as typeId, COUNT(s.id) AS surveyCount, AVG(s.question_1_7) AS surveyAverageOverallRating')
                    ->innerJoin('s.type', 't')
                    ->innerJoin('t.accommodation', 'a')
                    ->innerJoin('a.place', 'p')
                    ->where($expr->eq('p', ':place'))
                    ->andWhere($expr->gt('s.question_1_7', ':question_1_7'))
                    ->andWhere($expr->eq('s.reviewed', ':reviewed'))
                    ->andWhere($expr->eq('t.display', ':display'))
                    ->andWhere($expr->eq('a.display', ':display'))
                    ->andWhere($expr->eq('a.weekendSki', ':weekendSki'))
                    ->setParameters([
                        
                        'place'        => $place,
                        'question_1_7' => 0,
                        'reviewed'     => 1,
                        'display'      => 1,
                        'weekendSki'   => false,
                    ])
                    ->groupBy('t.id')
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
                        'weekendSki'   => false,
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
                        'weekendSki'   => false,
                    ])
                    ->getQuery();
        
        return $query->getSingleResult();
    }
}
