<?php
namespace AppBundle\Service\Api\User;

/**
 * @author Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since 0.0.2
 */
interface UserServiceRepositoryInterface
{
    /**
     * @param int $userId
     * @param array $fields
     * @param array $options
     * @return UserServiceDocumentInterface
     */
    public function get($userId, $fields = [], $options = []);
    
    /**
     * @param int $userId
     * @param array $options
     * @return UserServiceDocumentInterface
     */
	public function favorites($userId, $options = []);
    
    /**
     * @param int $userId
     * @return int
     */
	public function countFavorites($userId);
}