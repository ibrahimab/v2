<?php
namespace AppBundle\Entity\Search;
use       AppBundle\Service\Api\Search\SearchServiceRepositoryInterface;
use       AppBundle\Service\Api\Search\SearchBuilder;
use       AppBundle\Concern\SeasonConcern;
use       AppBundle\Concern\WebsiteConcern;
use       AppBundle\Entity\BaseRepository;
use       Doctrine\ORM\EntityManager;

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
        $limit  = $searchBuilder->block(SearchBuilder::BLOCK_LIMIT);
        $offset = $searchBuilder->block(SearchBuilder::BLOCK_OFFSET);
        
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->add('select', 't')
           ->add('from', self::ENTITY_TYPE . ' t')
           ->setMaxResults($limit)
           ->setFirstResult($offset);
        
        return $qb->getQuery()->getResult();
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