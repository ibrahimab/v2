<?php
namespace AppBundle\Document\Listing;
use		  AppBundle\Document\BaseRepository;
use		  AppBundle\Service\Api\Listing\ListingServiceRepositoryInterface;
use		  AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use		  Doctrine\ODM\MongoDB\DocumentManager;
use		  Doctrine\ODM\MongoDB\DocumentRepository;

class ListingRepository extends BaseRepository implements ListingServiceRepositoryInterface
{
	const FAVORITE_DOCUMENT = 'Favorite';

	private $allowedDocuments = [
		self::FAVORITE_DOCUMENT
	];

	/**
	 * @var DocumentManager
	 */
	private $documentManager;

	/**
	 * @var array
	 */
	private $repositoryCache;

	/**
	 * @param DocumentManager $documentManager
	 */
	public function __construct(DocumentManager $documentManager)
	{
		$this->documentManager = $documentManager;
	}

	/**
	 * @param string $documentName
	 * @return DocumentRepository
	 * @throws ListingRepositoryException
	 */
	public function getRepository($documentName)
	{
		if (!in_array($documentName, $this->allowedDocuments)) {
			throw new ListingRepositoryException(sprintf('%s is not allowed', $documentName));
		}

		if (isset($this->repositoryCache[$documentName])) {
			return $this->repositoryCache[$documentName];
		}

		return $this->documentManager->getRepository('AppBundle:Listing\\' . $documentName);
	}

	public function favorites($userId, $options = [])
	{
		$repository = $this->getRepository(self::FAVORITE_DOCUMENT);
		$limit		= self::getOption($options, 'limit', 20);

		return $repository->findBy(['user_id' => $userId], null, $limit);
	}

	public function favorite($userId, TypeServiceEntityInterface $type)
	{
		$repository = $this->getRepository(self::FAVORITE_DOCUMENT);
		return $repository->findOneBy(['user_id' => $userId, 'type' => $type->getId()]);
	}
}