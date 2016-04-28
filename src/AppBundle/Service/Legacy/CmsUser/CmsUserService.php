<?php
namespace AppBundle\Service\Legacy\CmsUser;

use AppBundle\Service\Legacy\CmsUser\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;


/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 */
class CmsUserService
{
    /**
     * @var Request|null
     */
    private $request;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var array
     */
    private $user;

    /**
     * @param RequestStack $request
     */
    public function __construct(RequestStack $requestStack, RepositoryInterface $repository)
    {
        $this->request    = $this->resolveRequest($requestStack);
        $this->repository = $repository;
    }

    /**
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->request->cookies->has('loginuser');
    }

    /**
     * @return boolean
     */
    public function shouldShowInternalInfo()
    {
        if ($this->request->query->get('hide_internal') === '1') {
            return false;
        } else {
            return $this->isLoggedIn();
        }
    }

    /**
     * @return integer
     * @throws NotFoundException
     */
    public function getUserId()
    {
        if (false === $this->isLoggedIn()) {
            throw new NotFoundException('User is not logged in, user ID could not be found');
        }

        $cookie = $this->request->cookies->get('loginuser');

        return intval($cookie['chalet']);
    }

    /**
     * @return array
     */
    public function getUser()
    {
        if (null === $this->user) {
            $this->user = $this->repository->getUser($this->getUserId());
        }

        return $this->user;
    }

    /**
     * @param RequestStack $requestStack
     *
     * @return Request
     */
    private function resolveRequest(RequestStack $requestStack)
    {
        return $requestStack->getCurrentRequest() ?: new Request();
    }
}
