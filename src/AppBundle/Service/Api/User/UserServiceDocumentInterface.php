<?php
namespace AppBundle\Service\Api\User;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * UserServiceDocumentInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
interface UserServiceDocumentInterface
{
    /**
     * @return \MongoId
     */
	public function getId();

    /**
     * @param int $user_id
     * @return UserServiceDocumentInterface
     */
	public function setUserId($user_id);

    /**
     * @return int
     */
	public function getUserId();

    /**
     * @param string $username
     * @return UserServiceDocumentInterface
     */
    public function setUsername($username);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $password
     * @return UserServiceDocumentInterface
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function addFavorite(TypeServiceEntityInterface $type);

    /**
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function removeFavorite(TypeServiceEntityInterface $type);

    /**
     * @param array
     * @return UserServiceDocumentInterface
     */
    public function setFavorites($favorites);

    /**
     * @return array
     */
    public function getFavorites();

    /**
     * @return int
     */
    public function totalFavorites();

    /**
     * Add type
     *
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function addViewed(TypeServiceEntityInterface $type);

    /**
     * @param array $viewed
     * @return UserServiceDocumentInterface
     */
    public function setViewed($viewed);

    /**
     * @return UserServiceDocumentInterface
     */
    public function getViewed();

    /**
     * @return int
     */
    public function totalViewed();

    /**
     * @param \MongoId $searchId
     * @return array
     */
    public function getSearch($searchId);

    /**
     * @return array
     */
    public function getSearches();

    /**
     * @param array $searches
     * @return UserServiceDocumentInterface
     */
    public function setSearches(Array $searches);

    /**
     * Add search
     *
     * @param array $search
     * @return UserServiceDocumentInterface
     */
    public function addSearch(Array $search);

    /**
     * remove search
     *
     * @param string $id
     * @return boolean
     */
    public function removeSearch($id);

    /**
     * clear searches
     *
     * @return boolean
     */
    public function clearSearches();

    /**
     * Count searches
     *
     * @return int
     */
    public function totalSearches();

    /**
     * @param \DateTime $createdAt
     * @return UserServiceDocumentInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     * @return UserServiceDocumentInterface
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}