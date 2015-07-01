<?php
namespace AppBundle\Entity\Option;
use       AppBundle\Entity\BaseRepositoryTrait;
use       AppBundle\Service\Api\Option\OptionServiceRepositoryInterface;
use       Doctrine\ORM\EntityManager;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class OptionRepository implements OptionServiceRepositoryInterface
{
    use BaseRepositoryTrait;

    /**
     * Constructor, injecting the EntityManager
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTravelInsurancesDescription()
    {
        $

        return null;
    }
}