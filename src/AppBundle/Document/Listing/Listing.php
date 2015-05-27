<?php
namespace AppBundle\Document\Listing;
use		  AppBundle\Service\Api\Listing\ListingServiceDocumentInterface;
use 	  Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * ListingDocument
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
/** @ODM\MappedSuperclass() */
class Listing implements ListingServiceDocumentInterface
{
	/**
	 * @ODM\Id
	 */
	private $_id;

	/**
	 * @ODM\String
	 */
	private $user_id;

	public function getId()
	{
		return $this->_id;
	}

	public function setUserId($user_id)
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getUserId()
	{
		return $this->user_id;
	}
}