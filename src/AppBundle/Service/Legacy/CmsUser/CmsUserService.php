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
     * time in seconds when a login will be valid
     *
     **/
    const LOGIN_VALID_TIME = 31536000;

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
     * @var boolean
     */
    private $isLoggedIn;

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

        if (null === $this->isLoggedIn) {

            $this->isLoggedIn = false;

            $userData = $this->getUser();

            if ($userData['userlevel'] > 0) {

                $uniqueid_cookie = $this->request->cookies->get('loginsessionid')['chalet'];

                $uniqueid_database = $userData['uniqueid_ip'];

                if (preg_match('@([0-9]+)_' . str_replace('.', '\.', $this->request->server->get('REMOTE_ADDR')) . '_' . $uniqueid_cookie . '@', $uniqueid_database, $regs)) {
                    if (intval($regs[1]) > (time() - self::LOGIN_VALID_TIME)) {
                        $this->isLoggedIn = true;
                    }
                }
            }
        }

        return $this->isLoggedIn;
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

        return $this->getUserIdFromCookie();

    }

    /**
     * get userid based in the loginuser[chalet]-cookie
     *
     * @return integer
     **/
    private function getUserIdFromCookie()
    {

        $cookie = $this->request->cookies->get('loginuser');
        return intval($cookie['chalet']);

    }

    /**
     * @return array
     */
    public function getUser()
    {
        if (null === $this->user) {
            $this->user = $this->repository->getUser($this->getUserIdFromCookie());
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
