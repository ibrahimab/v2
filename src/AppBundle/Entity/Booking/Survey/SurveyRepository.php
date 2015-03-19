<?php
namespace AppBundle\Entity\Booking\Survey;

use       AppBundle\Entity\BaseRepository;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
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
}
