<?php
namespace AppBundle\Document\Autocomplete;
use       AppBundle\Document\BaseRepositoryTrait;
use       AppBundle\Service\UtilsService;
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
    use BaseRepositoryTrait;

    /**
     * @var string
     */
    private $mongoDatabase;

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
        $results = [AutocompleteService::KIND_COUNTRY => [], AutocompleteService::KIND_REGION => [], AutocompleteService::KIND_PLACE => [], AutocompleteService::KIND_ACCOMMODATION => [], AutocompleteService::KIND_TYPE => []];

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
        $limit     = self::getOption($options, 'limit',  1);
        $offset    = self::getOption($options, 'offset', 0);
        $results   = [];

        // normalize search term
        $term      = UtilsService::normalizeText($term);

        // spaces: search for everything in between words
        $term      = str_replace(' ', '.*', $term);

        $nameRegex = new \MongoRegex('/.*' . $term . '.*/i');

        $collection = $this->collection();
        $rawResults = $collection->find([

            '$and' => [

                [
                    'type' => [
                        '$in' => $kinds,
                    ],
                ],

                [
                    '$or' => [

                        [
                            'searchable' => $nameRegex,
                            'locales'    => null,
                        ],

                        [
                            'searchable.' . $this->getLocale() => $nameRegex,
                            'locales' => [
                                '$ne' => null,
                            ],
                        ],

                        [
                            '$and' => [

                                [
                                    'alternative' => [
                                        '$exists' => true,
                                    ]
                                ],

                                [
                                    'alternative' => $nameRegex,
                                ],
                            ],
                        ],

                        [
                            '$and' => [

                                [
                                    'code' => [
                                        '$exists' => true,
                                    ],
                                ],

                                [
                                    'code' => $term,
                                ],
                            ],
                        ],
                    ],
                ],
            ],

        ])->limit(5)->sort(['order' => 1]);

        $results = [];

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

    public function collection()
    {
        return $this->getDocumentManager()->getConnection()->getMongo()->selectCollection($this->mongoDatabase, $this->getWebsite() . '.autocomplete');
    }

    public function setMongoDatabase($database)
    {
        $this->mongoDatabase = $database;
    }
}
