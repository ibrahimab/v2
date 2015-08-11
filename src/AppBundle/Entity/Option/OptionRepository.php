<?php
namespace AppBundle\Entity\Option;
use       AppBundle\Entity\BaseRepositoryTrait;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
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

    public function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
    }
    
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return string
     */
    public function getTravelInsurancesDescription()
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        $groupId    = ($this->getSeason() === SeasonConcern::SEASON_SUMMER ? self::OPTION_GROUP_SEASON_SUMMER : self::OPTION_GROUP_SEASON_DEFAULT);
       
        $qb->select('g.optie_groep_id, g.omschrijving, g.omschrijving_en, g.omschrijving_de, s.optie_soort_id, s.reisverzekering')
           ->from('optie_groep', 'g')
           ->join('g', 'optie_soort', 's', 'g.optie_soort_id = s.optie_soort_id')
           ->where($expr->eq('s.reisverzekering', ':travelInsurance'))
           ->andWhere($expr->eq('g.optie_groep_id', ':groupId'))
           ->setParameters([
       
               'travelInsurance' => 1,
               'groupId'         => $groupId,
           ]);
           
        $statement   = $qb->execute();
        $result      = $statement->fetch();
        $description = null;
        
        if (false !== $result) {
            
            switch ($this->getLocale()) {
                
                case 'nl':
                    $description = $result['omschrijving'];
                break;
                    
                case 'en':
                    $description = $result['omschrijving_en'];
                break;
                
                case 'de':
                    $description = $result['omschrijving_de'];
                break;
            }
        }
        
        return $description;
    }
    
    public function options($type)
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        
        $qb->select('vo.optie_soort_id, vo.snaam, vo.snaam_en, vo.snaam_de, vo.optie_onderdeel_id, vo.onaam, vo.onaam_en, vo.onaam_de')
           ->from('view_optie vo, optie_accommodatie a, optie_tarief ta, type t', '')
           ->where('vo.optie_soort_id = a.optie_soort_id')
           ->andWhere('vo.optie_groep_id = a.optie_groep_id')
           ->andWhere('t.accommodatie_id = a.accommodatie_id')
           ->andWhere($expr->eq('t.type_id', ':type'))
           ->andWhere($expr->gte('ta.week', ':week'))
           ->andWhere($expr->gte('ta.beschikbaar', ':available'))
           ->orderBy('vo.svolgorde, vo.snaam, vo.ovolgorde, vo.onaam')
           ->setParameters([
               
               'type'      => $type,
               'week'      => time(),
               'available' => 1,
           ]);
           dump($qb->getSQL());exit;
        $statement = $qb->execute();
        $locale    = $this->getLocale();
        $results   = $statement->fetchAll();
        $tree      = [];
        dump($results);exit;
        foreach ($results as $result) {
            
            switch ($locale) {
                
                case 'en':
                    
                    $kind = $result['snaam_en'];
                    $part = $result['onaam_en'];
                    
                break;
                
                case 'de':
                
                    $kind = $result['snaam_de'];
                    $part = $result['onaam_de'];
                    
                break;
                
                case 'nl':
                default:
                
                    $kind = $result['snaam'];
                    $part = $result['onaam'];
            }
            
            if (!isset($tree[$result['optie_soort_id']])) {
                $tree[$result['optie_soort_id']] = ['kind' => $kind, 'parts' => []];
            }
            
            $tree[$result['optie_soort_id']]['parts'][] = [
                
                'id'    => $result['optie_onderdeel_id'],
                'name'  => $part,
                'price' => $result['verkoop'],
            ];
        }
        
        // dump($tree);exit;
        
        return $tree;
    }
}