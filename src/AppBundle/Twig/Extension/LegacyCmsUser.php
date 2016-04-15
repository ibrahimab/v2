<?php
namespace AppBundle\Twig\Extension;

use       AppBundle\Service\Api\Type\TypeServiceEntityInterface;

/**
 *
 * extension used by Twig to get legacy cms user data
 *
 * @author  Jeroen Boschman <jeroen@webtastic.nl>
 * @package Chalet
 */
trait LegacyCmsUser
{
    /**
     * is the legacy cms user logged in?
     *
     * @return int
     */
    public function isLegacyCmsUserLoggedIn()
    {
        return $this->cmsUserService->isLoggedIn();
    }

    /**
     * get user data
     *
     * @return array
     */
    public function getCmsInfo()
    {
        $userinfo['user'] = $this->cmsUserService->getUser()['user'];
        $userinfo['firstname'] = $this->cmsUserService->getUser()['voornaam'];
        $userinfo['server'] = constant('wt_server_name');

        return $userinfo;
    }
}
