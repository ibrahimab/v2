<?php
namespace AppBundle\Document\Autocomplete;
use       AppBundle\Document\BaseRepository;
use       AppBundle\Service\Api\Autocomplete\AutocompleteService;
use       AppBundle\Service\Api\Autocomplete\AutocompleteServiceRepositoryInterface;
use       Doctrine\ODM\MongoDB\DocumentRepository;

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
     * Return all the autocomplete entries
     *
     * @return Array
     */
    public function all()
    {
        $qb = $this->createQueryBuilder();
        $qb->select('type', 'type_id')
           ->hydrate(false)
           ->eagerCursor(true)
           ->sort('order', 'asc');

        $raw     = $qb->getQuery()->execute();
        $results = [AutocompleteService::KIND_COUNTRY => [], AutocompleteService::KIND_REGION => [], AutocompleteService::KIND_PLACE => [], AutocompleteService::KIND_ACCOMMODATION];

        foreach ($raw as $row) {
            $results[$row['type']][] = $row;
        }

        return $results;
    }

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

        $nameRegex = new \MongoRegex('/.*' . $term . '.*/i');
        
        $qb = $this->createQueryBuilder();
        $qb->select()
           ->hydrate(false)
           ->eagerCursor(true)
           ->sort('order', 'asc');

        $type     = $qb->expr()->field('type')->in($kinds);

        $name     = $qb->expr()->addOr($qb->expr()->field('name')->equals($nameRegex)
                                                  ->field('locales')->equals(null))

                               ->addOr($qb->expr()->field('name.nl')->equals($nameRegex)
                                                  ->field('locales')->notEqual(null))

                               ->addOr($qb->expr()->field('code')->exists(true)
                                                  ->field('code')->equals($term));

        $season   = $qb->expr()->addOr($qb->expr()->field('season')->exists(false))
                               ->addOr($qb->expr()->addAnd($qb->expr()->field('season')->exists(true))
                                                  ->addAnd($qb->expr()->field('season')->equals($this->getSeason())));

        $websites = $qb->expr()->addOr($qb->expr()->field('websites')->exists(false))
                               ->addOr($qb->expr()->addAnd($qb->expr()->field('websites')->exists(true))
                                                  ->addAnd($qb->expr()->field('websites')->equals($this->getWebsite())));

        $qb->addAnd($type)
           ->addAnd($name)
           ->addAnd($season)
           ->addAnd($websites);

        $rawResults = $qb->getQuery()->execute()->toArray();
        $results    = [];

        foreach ($rawResults as $rawResult) {

            if (!isset($results[$rawResult['type']])) {
                $results[$rawResult['type']] = [];
            }

            $results[$rawResult['type']][] = $rawResult;
        }

        if (count($kinds) === 1) {
            $results = $results[$kinds[0]];
        }

        return $results;
    }
}