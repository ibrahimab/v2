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
     * @ODM\String
     */
    private $username;
    
    /**
     * @ODM\String
     */
    private $password;
    
    /**
     * @ODM\Collection
     */
    private $favorites;
    
    /**
     * @ODM\Collection
     */
    private $viewed;
    
    /**
     * @ODM\Date
     */
    private $created_at;
    
    /**
     * @ODM\Date
     */
    private $updated_at;

    public function __construct()
    {
        $this->favorites = [];
        $this->viewed    = [];
    }
    

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
    
    /**
     * {@InheritDoc}
     */
    public function setUsername($username)
    {
        $this->username = $username;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setPassword($password)
    {
        $this->password = $password;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setFavorites($favorites)
    {
        $this->favorites = $favorites;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getFavorites()
    {
        return $this->favorites;
    }
    
    /**
     * {@InheritDoc}
     */
    public function totalFavorites()
    {
        return count($this->favorites);
    }
    
    /**
     * {InheritDoc}
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getViewed()
    {
        return $this->viewed;
    }
    
    /**
     * {@InheritDoc}
     */
    public function totalViewed()
    {
        return count($this->viewed);
    }
    
    /**
     * {@InheritDoc}
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * {@InheritDoc}
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        
        return $this;
    }
    
    /**
     * {@InheritDoc}
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
}