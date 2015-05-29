<?php
namespace AppBundle\Document\User;
use		  AppBundle\Service\Api\User\UserServiceDocumentInterface;
use 	  Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * UserDocument
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
/** @ODM\Document(collection="users", repositoryClass="AppBundle\Document\User\UserRepository") */
class User implements UserServiceDocumentInterface
{
	/**
	 * @ODM\Id
	 */
	private $_id;

	/**
	 * @ODM\String
	 */
	private $user_id;

    /**
     * {@InheritDoc}
     */
	public function getId()
	{
		return $this->_id;
	}

    /**
     * {@InheritDoc}
     */
	public function setUserId($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

    /**
     * {@InheritDoc}
     */
	public function getUserId()
	{
		return $this->user_id;
	}
}