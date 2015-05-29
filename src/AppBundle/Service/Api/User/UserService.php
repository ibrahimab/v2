<?php
namespace AppBundle\Service\Api\User;
use       AppBundle\Service\Api\User\UserServiceDocumentInterface;
use       Symfony\Component\Security\Core\SecurityContextInterface;

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
     * @var SecurityContextInterface
     */
    private $securityContext;
    
    /**
     * @var UserServiceDocumentInterface
     */
    private $user;

    /**
     * Constructor
     *
     * @param UserServiceRepositoryInterface $userServiceRepository
     */
	public function __construct(UserServiceRepositoryInterface $userServiceRepository, SecurityContextInterface $securityContext)
	{
		$this->userServiceRepository = $userServiceRepository;
        $this->securityContext       = $securityContext;
	}
    
    /**
     * Get current user object
     *
     * @return UserServiceDocumentInterface|null
     */
    public function user()
    {
        // check if user cache is empty and if an actual user token is active
        if (null === $this->user && null !== ($token = $this->securityContext->getToken())) {
            $this->user = $this->get($token->getAttribute('_anon_tk'));
        }
        
        return $this->user;
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
		return $this->userServiceRepository->get($userId, $fields, $andWhere = []);
	}
}