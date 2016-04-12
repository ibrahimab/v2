<?php
namespace AppBundle\Service\Api\User;
use       AppBundle\Service\Api\User\UserServiceDocumentInterface;
use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;
use       Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.2
 */
class UserService
{
	/**
	 * @var UserServiceRepositoryInterface
	 */
	private $userServiceRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var UserServiceDocumentInterface
     */
    private $user;

    /**
     * Constructor
     *
     * @param UserServiceRepositoryInterface $userServiceRepository
     */
	public function __construct(UserServiceRepositoryInterface $userServiceRepository, TokenStorageInterface $tokenStorage)
	{
		$this->userServiceRepository = $userServiceRepository;
        $this->tokenStorage          = $tokenStorage;
	}

    /**
     * Get current user object
     *
     * @return UserServiceDocumentInterface|null
     */
    public function user()
    {
        // check if user cache is empty and if an actual user token is active
        if (null === $this->user && null !== ($token = $this->tokenStorage->getToken()) && true === $token->hasAttribute('_anon_tk')) {
            $this->user = $this->get($token->getAttribute('_anon_tk'));
        }

        return $this->user;
    }

    /**
     * Create a new user
     *
     * @param mixed $userId
     * @return UserServiceDocumentInterface
     */
    public function create($userId)
    {
        return $this->user = $this->userServiceRepository->create($userId);
    }

    /**
     * Get user object from mongo
     *
     * @param mixed $userId
     * @param array $fields
     * @param array $andWhere
     * @return UserServiceDocumentInterface
     */
	public function get($userId, $fields = [], $andWhere = [])
	{
		$this->user = $this->userServiceRepository->get($userId, $fields, $andWhere);

        return $this->user;
	}

    /**
     * Save search to mongo
     *
     * @param array $search
     * @return UserServiceDocumentInterface
     */
    public function saveSearch($search)
    {
        return $this->userServiceRepository->saveSearch($this->user(), $search);
    }

    /**
     * remove search
     *
     * @param string $id
     * @return boolean
     */
    public function removeSearch($id)
    {
        return $this->userServiceRepository->removeSearch($this->user(), $id);
    }

    /**
     * clear searches
     *
     * @param string $id
     * @return boolean
     */
    public function clearSearches()
    {
        return $this->userServiceRepository->clearSearches($this->user());
    }

    /**
     * Save viewed accommodation
     *
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function addViewedAccommodation(TypeServiceEntityInterface $type)
    {
        return $this->userServiceRepository->addViewedAccommodation($this->user(), $type);
    }

    /**
     * Save accommodation
     *
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function addFavoriteAccommodation(TypeServiceEntityInterface $type)
    {
        return $this->userServiceRepository->addFavoriteAccommodation($this->user(), $type);
    }

    /**
     * Remove accommodation
     *
     * @param TypeServiceEntityInterface $type
     * @return UserServiceDocumentInterface
     */
    public function removeFavoriteAccommodation(TypeServiceEntityInterface $type)
    {
        return $this->userServiceRepository->removeFavoriteAccommodation($this->user(), $type);
    }
}