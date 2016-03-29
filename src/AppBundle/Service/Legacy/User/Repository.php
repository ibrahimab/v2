<?php
namespace AppBundle\Service\Legacy\User;

use Doctrine\DBAL\Connection;
use PDO;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class Repository implements RepositoryInterface
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * Constructor
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param integer $userId
     *
     * @return array
     */
    public function getUser($userId)
    {
        $statement = $this->db->prepare('SELECT * FROM user WHERE user_id = :userId');
        $statement->execute(['userId' => $userId]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}