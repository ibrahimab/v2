<?php
namespace AppBundle\Document\Listing;
use		  Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * FileEntity
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
/** @ODM\Document(collection="listings.favorites") */
class Favorite extends Listing
{
	/**
	 * @ODM\Int
	 */
	private $type;

	/**
	 * @param integer $type
	 * @return Favorite
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}
}