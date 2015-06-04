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
        $limit   = self::getOption($options, 'limit',  1);
        $offset  = self::getOption($options, 'offset', 0);
		$results = [];

		foreach ($kinds as $kind) {

			$regex = new \MongoRegex('/.*' . $term .'.*/i');
			$qb = $this->createQueryBuilder();
			$qb->select('type', 'type_id')
			   ->hydrate(false)
			   ->eagerCursor(true)
			   ->skip($offset)
			   ->limit($limit)
			   ->sort('order', 'asc');

			$qb
				->field('type')
			        ->equals($kind)
			    ->addOr(
			    	$qb->expr()
			    	       ->addAnd(
			    	           $qb->expr()
			    	      	          ->field('name')
			    	      		          ->equals($regex)
			    	      		   	  ->field('locales')
			    	      			      ->equals(null)
			    		   )
			    )
			    ->addOr(
			        $qb->expr()
			    	       ->addAnd(
			    		       $qb->expr()
			    				     ->field('name.nl')
			    					     ->equals($regex)
			    					 ->field('locales')
			    						 ->notEqual(null)
			    	)
			);

	        $results[$kind] = $qb->getQuery()->execute();
		}

		if (count($kinds) === 1) {
			$results = $results[$kinds[0]];
		}

		return $results;
    }
}