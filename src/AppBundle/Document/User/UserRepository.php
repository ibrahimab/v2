<?php
namespace AppBundle\Document\User;
use		  AppBundle\Document\BaseRepositoryTrait;
use       AppBundle\Service\Api\User\UserServiceDocumentInterface;
use		  AppBundle\Service\Api\User\UserServiceRepositoryInterface;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
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
    use BaseRepositoryTrait;

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
        $searches = $user->getSearches();

        foreach ($searches as $savedSearch) {

            if ($savedSearch['search'] === $search) {

                // search is already saved
                return false;
            }
        }

        $user->addSearch($search);

        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();

        return true;
    }

    /**
     * {@InheritDoc}
     */
    public function removeSearch(UserServiceDocumentInterface $user, $id)
    {
        $result = $user->removeSearch($id);

        if (true === $result) {

            $dm = $this->getDocumentManager();
            $dm->persist($user);
            $dm->flush();
        }

        return $result;
    }

    /**
     * {@InheritDoc}
     */
    public function clearSearches(UserServiceDocumentInterface $user)
    {
        $user->clearSearches();

        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();

        return true;
    }

    /**
     * {@InheritDoc}
     */
    public function addViewedAccommodation(UserServiceDocumentInterface $user, TypeServiceEntityInterface $type)
    {
        $user->addViewed($type);

        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();

        return $user;
    }

    /**
     * {@InheritDoc}
     */
    public function addFavoriteAccommodation(UserServiceDocumentInterface $user, TypeServiceEntityInterface $type)
    {
        $user->addFavorite($type);

        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();

        return $user;
    }

    /**
     * {@InheritDoc}
     */
    public function removeFavoriteAccommodation(UserServiceDocumentInterface $user, TypeServiceEntityInterface $type)
    {
        $user->removeFavorite($type);

        $dm = $this->getDocumentManager();
        $dm->persist($user);
        $dm->flush();

        return $user;
    }
}