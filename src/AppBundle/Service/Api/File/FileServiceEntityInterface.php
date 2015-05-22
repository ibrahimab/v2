<?php
namespace AppBundle\Service\Api\File;

/**
 * FileServiceEntityInterface
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @since   0.0.1
 * @package Chalet
 */
interface FileServiceEntityInterface
{
	/**
	 * Getting primary MongoDB ID
	 *
	 * @return \MongoId
	 */
	public function get_Id();

	/**
	 * Setting file ID
	 *
	 * @param int $fileId
	 * @return FileServiceEntityInterface
	 */
	public function setFileId($fileId);

	/**
	 * Getting file ID
	 *
	 * @return string
	 */
	public function getFileId();

	/**
	 * Setting filename
	 *
	 * @param string $fileName
	 * @return FileServiceEntityInterface
	 */
	public function setFileName($fileName);

	/**
	 * Getting primary MongoDB ID
	 *
	 * @return string
	 */
	public function getFileName();

	/**
	 * Setting directory
	 *
	 * @param string $directory
	 * @return FileServiceEntityInterface
	 */
	public function setDirectory($directory);

	/**
	 * Getting directory
	 *
	 * @return string
	 */
	public function getDirectory();

	/**
	 * Setting kind
	 *
	 * @param string $kind
	 * @return FileServiceEntityInterface
	 */
	public function setKind($kind);

	/**
	 * Getting kind
	 *
	 * @return string
	 */
	public function getKind();

	/**
	 * Setting rank
	 *
	 * @param int $rank
	 * @return FileServiceEntityInterface
	 */
	public function setRank($rank);

	/**
	 * Getting rank
	 *
	 * @return int
	 */
	public function getRank();

	/**
	 * Setting width
	 *
	 * @param int $width
	 * @return FileServiceEntityInterface
	 */
	public function setWidth($width);

	/**
	 * Getting width
	 *
	 * @return int
	 */
	public function getWidth();

	/**
	 * Setting height
	 *
	 * @param int $height
	 * @return FileServiceEntityInterface
	 */
	public function setHeight($height);

	/**
	 * Getting height
	 *
	 * @return int
	 */
	public function getHeight();
}