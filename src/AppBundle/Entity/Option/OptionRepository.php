<?php
namespace AppBundle\Entity\Option;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Entity\BaseRepositoryTrait;
use       AppBundle\Service\Api\Option\OptionServiceRepositoryInterface;
use       Doctrine\ORM\EntityManager;
use       Doctrine\ORM\NoResultException;
use       Doctrine\ORM\Query;

/**
 * Option repository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class OptionRepository implements OptionServiceRepositoryInterface
{
    use BaseRepositoryTrait;

    const OPTION_GROUP_SEASON_SUMMER  = 369;
    const OPTION_GROUP_SEASON_DEFAULT = 42;

    /**
     * Constructor, injecting the EntityManager
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return string
     */
    public function getTravelInsurancesDescription()
    {
        $qb      = $this->entityManager->createQueryBuilder();
        $expr    = $qb->expr();
        $groupId = ($this->getSeason() === SeasonConcern::SEASON_SUMMER ? self::OPTION_GROUP_SEASON_SUMMER : self::OPTION_GROUP_SEASON_DEFAULT);

        $qb->select('partial g.{id, description, englishDescription, germanDescription}, partial k.{id, travelInsurance}')
           ->from('AppBundle\Entity\Option\Group', 'g')
           ->innerJoin('g.kind', 'k')
           ->where($expr->eq('k.travelInsurance', ':travelInsurance'))
           ->andWhere($expr->eq('g.id', ':groupId'))
           ->setMaxResults(1)
           ->setParameters([

               'travelInsurance' => 1,
               'groupId'         => $groupId,
           ]);

        try {

            $result      = $qb->getQuery()->getSingleResult();
            $description = $result->getLocaleDescription($this->getLocale());

        } catch (NoResultException $exception) {
            $description = null;
        }

        return $description;
    }

    public function accommodation($accommodation)
    {
        $qb     = $this->entityManager->createQueryBuilder();
        $expr   = $qb->expr();

        $resale = (true === $this->getWebsiteConcern()->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE) ? 'ok.availableResale' : 'ok.availableDirectClients');

        $qb->select('partial oa.{id}, partial ok.{id, name, englishName, germanName, description, englishDescription, germanDescription}, og.{id}')
           ->from('AppBundle\Entity\Option\Accommodation', 'oa')
           ->join('oa.kinds', 'ok')
           ->join('oa.group', 'og')
           ->where($expr->eq('oa', ':accommodation'))
           ->andWhere($expr->eq($resale, ':resale'))
           ->andWhere($expr->neq('ok.' . $this->getLocaleField('name'), ':name'))
           ->orderBy('ok.order, ok.' . $this->getLocaleField('name'))
           ->setParameters([

               'accommodation'           => $accommodation,
               'resale'                  => true,
               'name'                    => '',
           ]);

        $kinds   = $qb->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);
        $kindIds = array_map(function($kind) {
            return $kind['id'];
        }, $kinds);

        dump($kindIds);
        $kindIds = [7];

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('partial os.{id, name, englishName, germanName}')
           ->from('AppBundle\Entity\Option\Section', 'os')
           ->join('os.group', 'og')
           ->where($expr->in('og.kind', ':kind'))
           ->andWhere($expr->eq('os.showOnAccommodationPage', ':showOnAccommodationPage'))
           ->andWhere($expr->eq('os.active', ':active'))
           ->andWhere($expr->neq('os.' . $this->getLocaleField('name'), ':name'))
           ->orderBy('os.order, os.' . $this->getLocaleField('name'))
           ->setParameters([

               'showOnAccommodationPage' => true,
               'active'                  => true,
               'kind'                    => $kindIds,
               'name'                    => '',
           ]);
           dump($qb->getQuery()->getSQL());
           dump($qb->getQuery()->getResult(Query::HYDRATE_ARRAY));exit;
        // dump($qb->getQuery()->getResult(Query::HYDRATE_ARRAY));exit;
    }
}