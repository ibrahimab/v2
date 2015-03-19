<?php
namespace AppBundle\Service\Api\Booking\Survey;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

interface SurveyServiceRepositoryInterface
{
    /**
     * @param TypeServiceEntityInterface $type
     * @return integer
     */
    public function statsByType(TypeServiceEntityInterface $type);
    
    /**
     * @param TypeServiceEntityInterface[] $types
     * @return array
     */
    public function statsByTypes($types);
}