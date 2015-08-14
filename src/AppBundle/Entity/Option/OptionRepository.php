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
    
    private $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function getEntityManager()
    {
        return $this->manager;
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
    
    public function options($accommodationId, $weekend = null)
    {
        $website    = $this->getWebsiteConcern();
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        
        $qb->select('vo.optie_soort_id, vo.snaam, vo.snaam_en, vo.snaam_de, vo.optie_onderdeel_id, vo.onaam, vo.onaam_en, vo.onaam_de, ta.verkoop')
           ->from('optie_accommodatie a, view_optie vo, optie_soort s, optie_onderdeel o, optie_tarief ta', '')
           ->where('a.optie_soort_id = vo.optie_soort_id')
           ->where('a.optie_soort_id = s.optie_soort_id')
           ->andWhere('a.optie_groep_id = vo.optie_groep_id')
           ->andWhere('vo.optie_onderdeel_id = o.optie_onderdeel_id')
           ->andWhere('vo.optie_onderdeel_id = ta.optie_onderdeel_id')
           ->andWhere($expr->eq('a.accommodatie_id', ':accommodation'))
           ->andWhere($expr->gte('ta.week', ':week'))
           ->andWhere($expr->eq('ta.beschikbaar', ':available'))
           ->andWhere('o.tonen_accpagina = 1')
           ->andWhere('o.actief = 1')
           ->orderBy('vo.svolgorde, vo.snaam, vo.ovolgorde, vo.onaam')
           ->setParameters([
               
               'accommodation' => $accommodationId,
               'week'          => time(),
               'available'     => 1,
           ]);
           
        if ($website->getConfig(WebsiteConcern::WEBSITE_CONFIG_TRAVEL_INSURANCE) !== 1) {
            $qb->andWhere('s.reisverzekering = 0');
        }
        
        if (true === $website->getConfig(WebsiteConcern::WEBSITE_CONFIG_RESALE)) {
            $qb->andWhere('s.beschikbaar_wederverkoop = 1');
        } else {
            $qb->andWhere('s.beschikbaar_directeklanten = 1');
        }
           
        $statement = $qb->execute();
        $locale    = $this->getLocale();
        $results   = $statement->fetchAll();
        $tree      = [];
        
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
                $tree[$result['optie_soort_id']] = ['name' => $kind, 'parts' => []];
            }
            
            $tree[$result['optie_soort_id']]['parts'][$result['optie_onderdeel_id']] = [
                
                'id'        => $result['optie_onderdeel_id'],
                'name'      => $part,
                'price'     => abs($result['verkoop']),
                'discount'  => ($result['verkoop'] < 0),
                'free'      => ($result['verkoop'] != 0),
            ];
        }
        
        return $tree;
    }
}