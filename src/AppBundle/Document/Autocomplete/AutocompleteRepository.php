<?php
namespace AppBundle\Document\Autocomplete;
use       AppBundle\Document\BaseRepository;
use       AppBundle\Service\Api\Autocomplete\AutocompleteServiceRepositoryInterface;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * Autocomplete Repository
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class AutocompleteRepository extends DocumentRepository implements AutocompleteServiceRepositoryInterface
{
    use BaseRepository;
    
    /**
     * Search for autocomplete results
     *
     * @param  string $term
     * @param  array  $kinds
     * @param  array  $options
     * @return TypeServiceEntityInterface[]|AccommodationEntityInterface[]
     */
    public function search($term, $kinds, $options = [])
    {
        $limit  = self::getOption($options, 'limit',  1);
        $offset = self::getOption($options, 'offset', 0);
        
        $qb = $this->createQueryBuilder();
        $qb->select('type', 'type_id')
           ->hydrate(false)
           ->field('type')->in($kinds)
           ->where()
           ->orX()
               ->eq()->field('name')->equals(new \MongoRegex('/.*' . $term .'.*/i'))->andWhere()->field('locales')->equals(null)->end()
               ->eq()->field('name.nl')->equals(new \MongoRegex('/.*' . $term .'.*/i'))->andWhere()->field('locales')->notEqual(null)->end()
           ->eagerCursor(true)
           ->skip($offset)
           ->limit($limit)
           ->sort('order', 'asc');
        
        if ($kids === ['type']) {
            dump($qb->getQuery()->getQuery());
            exit;
        }
            
        return $qb->getQuery()->execute();
    } 
}