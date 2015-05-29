<?php
namespace AppBundle\Document\User;
use		  AppBundle\Document\BaseRepository;
use		  AppBundle\Service\Api\User\UserServiceRepositoryInterface;
use		  Doctrine\ODM\MongoDB\DocumentManager;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

/**
 * @author Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since 0.0.2
 */
class UserRepository extends DocumentRepository implements UserServiceRepositoryInterface
{
    use BaseRepository;
    
    /**
     * {@InheritDoc}
     */
    public function get($userId, $fields = [], $options = [])
    {
        return $this->findOneBy(['user_id' => $userId]);
    }
    
    /**
     * {@InheritDoc}
     */
    public function favorites($userId, $options = [])
    {
        return $this->get($userId, ['favorites' => 1], $options);
    }
    
    /**
     * {@InheritDoc}
     */
    public function countFavorites($userId)
    {
        return $this->createQueryBuilder()->count(['user_id' => $userId])->getQuery()->execute();
    }
}