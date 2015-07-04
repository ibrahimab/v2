<?php
namespace AppBundle\Service;
use       AppBundle\Service\Mongo\MongoService;
use       Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Monitoring service
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class MonitorService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Database check
     *
     * @return boolean
     */
    public function database()
    {
        try {

            $generalSettingsService = $this->container->get('app.api.general.settings');
            $database               = $generalSettingsService->monitorDatabase();

        } catch (\Exception $exception) {
            $database = false;
        }

        return $database;
    }

    /**
     * Redis check
     *
     * @return boolean
     */
    public function redis()
    {
        return $this->container->get('old.redis.wrapper')->monitor();
    }

    /**
     * MongoDB check
     *
     * @return boolean
     */
    public function mongo()
    {
        $document = [

            '_id'  => new \MongoId(),
            'hash' => time() . '-' . md5('monitor-mongodb-' . time()),
        ];

        $mongoService = $this->container->get('app.mongo');
        $mongoService->setDatabase(MongoService::LOCAL_DATABASE_NAME)
                     ->setCollection('monitoring')
                     ->add($document);

        $comparisonDocument = $mongoService->findOne(['_id' => $document['_id']]);

        return (null !== $comparisonDocument && $document['hash'] === $comparisonDocument['hash']);
    }
}