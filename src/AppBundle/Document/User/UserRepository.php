<?php
namespace AppBundle\Document\User;
use		  AppBundle\Document\BaseRepository;
use       AppBundle\Service\Api\User\UserServiceDocumentInterface;
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
    public function get($userId, $fields = [], $andWhere = [])
    {
        return $this->findOneBy(array_merge(['user_id' => $userId], $andWhere), ['fields' => $fields]);
    }
    
    /**
     * {@InheritDoc}
     */
    public function create($userId)
    {
        if (null !== $this->get($userId, ['user_id' => 1])) {
            throw new UserRepositoryException(sprintf('User already exists with ID=%s', $userId));
        }
        
        $user = new User();
        $user->setUserId($userId)
            ->setFavorites([])
            ->setViewed([])
            ->setCreatedAt($now = new \DateTime('now'))
            ->setUpdatedAt($now);
        
        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();
        
        return $user;
    }
    
    /**
     * {@InheritDoc}
     */
    public function saveSearch(UserServiceDocumentInterface $user, $search)
    {
        $user->addSearch($search);
        
        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();
        
        return $user;
    }
}