<?php
namespace AppBundle\Service\Mongo;
use       Doctrine\MongoDB\Connection;

/**
 * Monitoring service
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class MongoService
{
    /**
     * @const string
     */
    const LOCAL_DATABASE_NAME = 'local';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var \MongoDB
     */
    private $db;

    /**
     * @var \MongoCollection
     */
    private $collection;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Set database
     *
     * @param string $name
     * @return MongoService
     */
    public function setDatabase($name)
    {
        $this->db = $this->connection->{$name};

        return $this;
    }

    /**
     * Get current database
     *
     * @return \MongoDB
     */
    public function getDatabase()
    {
        return $this->db;
    }

    /**
     * Set collection
     *
     * @param string $name
     * @return MongoService
     */
    public function setCollection($name)
    {
        if (null === $this->getDatabase()) {
            throw new MongoServiceException('You have not selected a database yet');
        }

        $this->collection = $this->db->selectCollection($name);

        return $this;
    }

    /**
     * Get current collection
     *
     * @return MongoService
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Add document
     *
     * @param array $document
     * @return array
     */
    public function add(Array $document)
    {
        return $this->getCollection()->insert($document);
    }

    /**
     * Find document(s)
     *
     * @param mixed
     * @return \MongoCursor
     */
    public function find()
    {
        return call_user_func_array([$this->getCollection(), 'find'], func_get_args());
    }

    /**
     * Find single document
     *
     * @param mixed
     * @return array|null
     */
    public function findOne()
    {
        return call_user_func_array([$this->getCollection(), 'findOne'], func_get_args());
    }
}