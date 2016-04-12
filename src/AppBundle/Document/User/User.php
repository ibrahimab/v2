<?php
namespace AppBundle\Document\User;
use		  AppBundle\Service\Api\User\UserServiceDocumentInterface;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use 	  Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use       JMS\Serializer\Annotation AS JMS;

/**
 * UserDocument
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
/**
 * @ODM\Document(collection="users", repositoryClass="AppBundle\Document\User\UserRepository")
 * @JMS\ExclusionPolicy("none")
 * @JMS\AccessType("public_method")
 */
class User implements UserServiceDocumentInterface
{
	/**
	 * @ODM\Id
     * @JMS\Exclude
     * @JMS\AccessType("property")
	 */
	private $_id;

	/**
	 * @ODM\String
     * @JMS\Exclude
     * @JMS\AccessType("property")
	 */
	private $user_id;

    /**
     * @ODM\String
     */
    private $username;

    /**
     * @ODM\String
     * @JMS\Exclude
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
     * @ODM\Collection
     */
    private $searches;

    /**
     * @ODM\Date
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     * @JMS\Accessor(getter="getCreatedAt", setter="setCreatedAt")
     */
    private $created_at;

    /**
     * @ODM\Date
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     * @JMS\Accessor(getter="getCreatedAt", setter="setCreatedAt")
     */
    private $updated_at;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->favorites = [];
        $this->viewed    = [];
        $this->searches  = [];
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
    public function addFavorite(TypeServiceEntityInterface $type)
    {
        if (!in_array($type->getId(), $this->favorites)) {
            $this->favorites[] = $type->getId();
        }

        return $this;
    }

    /**
     * {@InheritDoc}
     */
    public function removeFavorite(TypeServiceEntityInterface $type)
    {
        if (in_array($type->getId(), $this->favorites)) {

            foreach ($this->favorites as $key => $favorite) {

                if ($favorite === $type->getId()) {
                    unset($this->favorites[$key]);
                }
            }
        }

        return $this;
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
     * {@InheritDoc}
     */
    public function addViewed(TypeServiceEntityInterface $type)
    {
        if (!in_array($type->getId(), $this->viewed)) {
            $this->viewed[] = $type->getId();
        }

        return $this;
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
    public function getSearch($searchId)
    {
        foreach ($this->searches as $search) {

            if ((string)$search['_id'] === (string)$searchId) {
                return $search;
            }
        }

        return null;
    }

    /**
     * {@InheritDoc}
     */
    public function getSearches()
    {
        return $this->searches;
    }

    /**
     * {@InheritDoc}
     */
    public function setSearches(Array $searches)
    {
        $documents = [];
        foreach ($searches as $search) {
            $documents[] = ['_id' => new \MogoId(), 'search' => $search];
        }

        $this->searches = $documents;
    }

    /**
     * {@InheritDoc}
     */
    public function addSearch(Array $search)
    {
        array_push($this->searches, ['_id' => new \MongoId(), 'search' => $search]);
    }

    /**
     * {@InheritDoc}
     */
    public function removeSearch($id)
    {
        foreach ($this->searches as $key => $search) {

            if ((string)$search['_id'] === $id) {

                unset($this->searches[$key]);
                return true;
            }
        }

        return false;
    }

    /**
     * @return boolean
     */
    public function clearSearches()
    {
        $this->searches = [];
        return true;
    }

    /**
     * {@InheritDoc}
     */
    public function totalSearches()
    {
        $total = 0;

        foreach ($this->searches as $search) {

            $hasSearch = false;

            foreach ($search['search'] as $item) {

                if (false !== $item) {
                    $hasSearch = true;
                }
            }

            if (true === $hasSearch) {
                $total += 1;
            }
        }

        return $total;
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