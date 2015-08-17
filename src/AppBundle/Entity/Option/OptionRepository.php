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
    
    public function options($accommodationId, $season = null, $weekend = null)
    {
        $website    = $this->getWebsiteConcern();
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        
        $qb->select('vo.optie_soort_id, vo.optie_groep_id, vo.snaam, vo.snaam_en, vo.snaam_de, vo.optie_onderdeel_id, vo.onaam, vo.onaam_en, vo.onaam_de')
           ->from('optie_accommodatie a, view_optie vo, optie_soort s, optie_onderdeel o', '')
           ->where('a.optie_soort_id = vo.optie_soort_id')
           ->where('a.optie_soort_id = s.optie_soort_id')
           ->andWhere('a.optie_groep_id = vo.optie_groep_id')
           ->andWhere('vo.optie_onderdeel_id = o.optie_onderdeel_id')
           ->andWhere($expr->eq('a.accommodatie_id', ':accommodation'))
           ->andWhere('o.tonen_accpagina = 1')
           ->andWhere('o.actief = 1')
           ->orderBy('vo.svolgorde, vo.snaam, vo.ovolgorde, vo.onaam')
           ->setParameter('accommodation', $accommodationId);
           
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
        $parts     = [];
        
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
                $tree[$result['optie_soort_id']] = ['name' => $kind, 'groupId' => $result['optie_groep_id'], 'parts' => []];
            }
            
            $tree[$result['optie_soort_id']]['parts'][$result['optie_onderdeel_id']] = [
                
                'id'        => $result['optie_onderdeel_id'],
                'name'      => $part,
            ];
            
            $parts[] = $result['optie_onderdeel_id'];
        }
        
        $qb = $connection->createQueryBuilder();
        
        $qb->select('ta.optie_onderdeel_id, ta.week, ta.verkoop')
           ->from('optie_tarief ta, optie_onderdeel o', '')
           ->where('ta.optie_onderdeel_id = o.optie_onderdeel_id')
           ->andWhere($expr->eq('ta.beschikbaar', ':available'))
           ->andWhere($expr->in('ta.optie_onderdeel_id', $parts))
           ->setParameter('available', 1);
           
        if (null !== $season) {
            
            $qb->andWhere($expr->eq('ta.seizoen_id', ':season'));
            $qb->setParameter('season', $season);
        }
        
        if (null === $weekend) {
            
            $qb->andWhere($expr->gte('ta.week', ':week'))
               ->setParameter('week' , time());
            
        } else {
            
            $qb->andWhere($expr->eq('ta.week', ':week'))
               ->setParameter('week', $weekend);
        }
           
        $statement = $qb->execute();
        $results   = $statement->fetchAll();
        $prices    = [];
        $cache     = [];
        
        foreach ($results as $result) {
            
            $result['verkoop'] = floatval($result['verkoop']);
            
            if (!isset($prices[$result['optie_onderdeel_id']])) {
                
                $prices[$result['optie_onderdeel_id']] = [
                    
                    'prices' => [],
                    'price'  => $result['verkoop'],
                ];
            }
            
            $prices[$result['optie_onderdeel_id']]['prices'][] = $result['verkoop'];
        }
        
        foreach ($prices as $id => $data) {
            
            if (count(array_unique($data['prices'])) > 1) {
                $prices[$id] = false;
            } else {
                $prices[$id] = $data['price'];
            }
        }

        foreach ($tree as $kind => $data) {
            
            foreach ($data['parts'] as $id => $row) {
                
                if (isset($prices[$id])) {
                    
                    $tree[$kind]['parts'][$id]['price']    = abs($prices[$id]);
                    $tree[$kind]['parts'][$id]['discount'] = ($prices[$id] < 0);
                    $tree[$kind]['parts'][$id]['free']     = ($prices[$id] === 0.0);
                    
                } else {
                    
                    unset($tree[$kind]['parts'][$id]);
                    
                    if (count($tree[$kind]['parts']) === 0) {
                        unset($tree[$kind]);
                    }
                }
            }
        }
        
        return $tree;
    }
    
    /**
     * @param integer $optionId
     * @return string
     */
    public function option($optionId)
    {
        $connection = $this->getEntityManager()->getConnection();
        $qb         = $connection->createQueryBuilder();
        $expr       = $qb->expr();
        
        $qb->select('s.naam, s.omschrijving AS somschrijving, s.omschrijving_en AS somschrijving_en, s.omschrijving_de AS somschrijving_de,
                     g.omschrijving AS gomschrijving, g.omschrijving_en AS gomschrijving_en, g.omschrijving_de AS gomschrijving_de')
           ->from('optie_soort s, optie_groep g', '')
           ->where('g.optie_soort_id = s.optie_soort_id')
           ->andWhere($expr->eq('g.optie_groep_id', ':optionId'))
           ->setParameter('optionId', $optionId);
           
        $statement   = $qb->execute();
        $result      = $statement->fetch();
        $locale      = $this->getLocale();
        $description = '';
        
        if (false !== $result) {
            
            switch ($locale) {
                
                case 'en':
                
                    $description  = nl2br($result['somschrijving_en']);
                    $description .= (trim($result['somschrijving_en']) !== '' ? '<br />' : '');
                    $description .= nl2br($result['gomschrijving_en']);
                    
                break;
                
                case 'de':
            
                    $description  = nl2br($result['somschrijving_de']);
                    $description .= (trim($result['somschrijving_de']) !== '' ? '<br />' : '');
                    $description .= nl2br($result['gomschrijving_de']);
                    
                break;
                
                case 'nl':
                default:
                    
                    $description  = nl2br($result['somschrijving']);
                    $description .= (trim($result['somschrijving']) !== '' ? '<br />' : '');
                    $description .= nl2br($result['gomschrijving']);
            }
        }
        
        return ['name' => $result['naam'], 'description' => $description];
    }
}