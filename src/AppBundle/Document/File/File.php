<?php
namespace AppBundle\Document\File;
use		  AppBundle\Service\Api\File\FileServiceEntityInterface;
use 	  Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * FileEntity
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
/** @ODM\MappedSuperclass */
class File implements FileServiceEntityInterface
{
	/**
	 * @ODM\Id(strategy="AUTO")
	 */
	private $_id;

	/** @ODM\Int */
	private $file_id;

	/** @ODM\String */
	private $kind;

	/** @ODM\Int */
	private $rank;

	/** @ODM\String */
	private $filename;

	/** @ODM\String */
	private $directory;

	/** @ODM\Int */
	private $width;

	/** @ODM\Int */
	private $height;


	/**
	 * {@InheritDoc}
	 */
	public function get_Id()
	{
		return $this->_id;
	}

	/**
	 * {@InheritDoc}
	 */
	public function setFileId($fileId)
	{
		$this->fileId = $fileId;

		return $this;
	}

	/**
	 * {@InheritDoc}
	 */
	public function getFileId()
	{
		return $this->fileId;
	}

	/**
	 * {@InheritDoc}
	 */
	public function setFileName($filename)
	{
		$this->filename = $filename;

		return $this;
	}

	/**
	 * {@InheritDoc}
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * {@InheritDoc}
	 */
	public function setDirectory($directory)
	{
		$this->directory = $directory;

		return $this;
	}

	/**
	 * {@InheritDoc}
	 */
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	 * {@InheritDoc}
	 */
	public function setKind($kind)
	{
		$this->kind = $kind;

		return $this;
	}

	/**
	 * {@InheritDoc}
	 */
	public function getKind()
	{
		return $this->kind;
	}

	/**
	 * {@InheritDoc}
	 */
	public function setRank($rank)
	{
		$this->rank = $rank;

		return $this;
	}

	/**
	 * {@InheritDoc}
	 */
	public function getRank()
	{
		return $this->rank;
	}

	/**
	 * {@InheritDoc}
	 */
	public function setWidth($width)
	{
		$this->width = $width;

		return $this;
	}

	/**
	 * {@InheritDoc}
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * {@InheritDoc}
	 */
	public function setHeight($height)
	{
		$this->height = $height;

		return $this;
	}

	/**
	 * {@InheritDoc}
	 */
	public function getHeight()
	{
		return $this->height;
	}
}