<?php
namespace AppBundle\Entity\Search;
use       AppBundle\Service\Api\Search\SearchServiceRepositoryInterface;
use       AppBundle\Service\Api\Search\SearchService;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Entity\BaseRepository;
use       Doctrine\ORM\EntityManager;
use       Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Search repository
 * 
 * This is the search repository
 *
 * @author  Ibrahim Abdullah
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class SearchRepository implements SearchServiceRepositoryInterface
{   
    /**
     * @const string
     */
    const ENTITY_COUNTRY       = 'AppBundle:Country\Country';
    
    /**
     * @const string
     */
    const ENTITY_REGION        = 'AppBundle:Region\Region';
    
    /**
     * @const string
     */
    const ENTITY_PLACE         = 'AppBundle:Place\Place';
    
    /**
     * @const string
     */
    const ENTITY_ACCOMMODATION = 'AppBundle:Accommodation\Accommodation';
    
    /**
     * @const string
     */
    const ENTITY_TYPE          = 'AppBundle:Type\Type';
    
    /**
     * @const string
     */
    const SORT_BY_DEFAULT      = SearchBuilder::SORT_BY_ACCOMMODATION_NAME;
                               
    /**                        
     * @const string           
     */                        
    const SORT_ORDER_DEFAULT   = SearchBuilder::SORT_ORDER_ASC;
    
    /**
     * @var integer
     */
    protected $season;
    
    /**
     * @var string
     */
    protected $website;
    
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
     * Search
     *
     * @param SearchBuilder $searchBuilder
     * @return array
     */
    public function search(SearchBuilder $searchBuilder)
    {
        $limit       = $searchBuilder->block(SearchBuilder::BLOCK_LIMIT);
        $offset      = $searchBuilder->block(SearchBuilder::BLOCK_OFFSET);
        $sort_by     = $searchBuilder->block(SearchBuilder::BLOCK_SORT_BY, self::SORT_BY_DEFAULT);
        $sort_order  = $searchBuilder->block(SearchBuilder::BLOCK_SORT_ORDER, self::SORT_ORDER_DEFAULT);

        $qb   = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();
        
        $qb->add('select', 't,
                            partial a.{id, name, shortDescription, englishShortDescription, germanShortDescription}, 
                            partial p.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName}, 
                            partial r.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName}, 
                            partial c.{id, name, englishName, germanName, seoName, englishSeoName, germanSeoName}')
           ->add('from', self::ENTITY_ACCOMMODATION . ' a')
           ->innerJoin('a.types', 't')
           ->innerJoin('a.place',  'p')
           ->innerJoin('p.region', 'r')
           ->innerJoin('p.country', 'c')
           ->where($expr->eq('t.display', ':display'))
           ->andWhere($expr->eq('t.displaySearch', ':displaySearch'))
           ->setMaxResults($limit)
           ->setFirstResult($offset)
           ->having($expr->gt('SIZE(a.types)', 1))
           ->setParameters([
               
               'display'       => true,
               'displaySearch' => true,
           ]);
           
        if (null !== ($sort_field = $this->sortField($sort_by))) {
            $qb->orderBy($sort_field, $sort_order);
        }
        
        $paginator    = new Paginator($qb, true);
        $paginator->page = [
            
            'current' => (round($offset / $limit) + 1),
            'last'    => round(count($paginator) / $limit),
        ];
        
        return $paginator;
    }
    
    public function sortField($sort)
    {
        $field = null;
        switch ($sort) {
            
            case SearchBuilder::SORT_BY_ACCOMMODATION_NAME:
                $field = 'a.name';
            break;
        }
        
        return $field;
    }
    
    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
    
    /**
     * Getting type repository
     *
     * @return BaseRepository
     */
    public function getRepository($entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    /**
     * Setting season
     *
     * @param SeasonConcern $seasonConcern
     * @return void
     */
    public function setSeason(SeasonConcern $seasonConcern)
    {
        $this->season = $seasonConcern->get();
    }

    /**
     * Getting season
     *
     * @return integer
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Setting website
     *
     * @param WebsiteConcern $websiteConcern
     */
    public function setWebsite(WebsiteConcern $websiteConcern)
    {
        $this->website = $websiteConcern->get();
    }

    /**
     * Getting website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Getting either the option passed in or the default
     *
     * @param array $options
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getOption($options, $key, $default = null)
    {
        return isset($options[$key]) ? $options[$key] : $default;
    }
}