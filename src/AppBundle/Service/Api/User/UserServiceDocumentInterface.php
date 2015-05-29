<?php
namespace AppBundle\Service\Api\User;

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
}