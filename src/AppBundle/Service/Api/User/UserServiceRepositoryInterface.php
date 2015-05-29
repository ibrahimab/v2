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
     * @param mixed $userId
     * @param array $fields
     * @param array $andWhere
     * @return UserServiceDocumentInterface
     */
    public function get($userId, $fields = [], $andWhere = []);
}