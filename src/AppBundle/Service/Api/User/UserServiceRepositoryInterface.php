<?php
namespace AppBundle\Service\Api\User;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * @author Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since 0.0.2
 */
interface UserServiceRepositoryInterface
{
    /**
     * @param mixed $userId
     * @param array $fields
     * @param array $andWhere
     * @return UserServiceDocumentInterface
     */
    public function get($userId, $fields = [], $andWhere = []);

    /**
     * @param mixed $userId
     * @return UserServiceDocumentInterface
     */
    public function create($userId);

    /**
     * Save search to mongo
     *
     * @param mixed $userId
     * @param array $search
     * @return UserServiceDocumentInterface
     */
    public function saveSearch(UserServiceDocumentInterface $user, $search);

    /**
     * remove search
     *
     * @param UserServiceDocumentInterface $user
     * @param string $id
     * @return boolean
     */
    public function removeSearch(UserServiceDocumentInterface $user, $id);

    /**
     * clear searches
     *
     * @param UserServiceDocumentInterface $userId
     * @return boolean
     */
    public function clearSearches(UserServiceDocumentInterface $user);

    /**
     * Save viewed accommodation
     *
     * @param UserServiceDocumentInterface $user
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function addViewedAccommodation(UserServiceDocumentInterface $user, TypeServiceEntityInterface $type);

    /**
     * Save accommodation
     *
     * @param UserServiceDocumentInterface $user
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function addFavoriteAccommodation(UserServiceDocumentInterface $user, TypeServiceEntityInterface $type);

    /**
     * Remove accommodation
     *
     * @param UserServiceDocumentInterface $user
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function removeFavoriteAccommodation(UserServiceDocumentInterface $user, TypeServiceEntityInterface $type);
}