<?php
namespace AppBundle\Service\Api\User;
use 	  AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 * @author Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.2
 * @since   0.0.2
 */
class UserService
{
	/**
	 * @var UserServiceRepositoryInterface
	 */
	private $userServiceRepository;

    /**
     * Constructor
     *
     * @param UserServiceRepositoryInterface $userServiceRepository
     */
	public function __construct(UserServiceRepositoryInterface $userServiceRepository)
	{
		$this->userServiceRepository = $userServiceRepository;
	}

    /**
     * {@InheritDoc}
     */
	public function get($userId, $fields = [], $options = [])
	{
		return $this->userServiceRepository->get($userId, $fields, $options = []);
	}

    /**
     * {@InheritDoc}
     */
	public function favorites($userId, $options = [])
	{
		return $this->userServiceRepository->favorites($userId, $options);
	}
	
    /**
     * {@InheritDoc}
     */
	public function countFavorites($userId)
	{
		return $this->userServiceRepository->countFavorites($userId);
	}
}