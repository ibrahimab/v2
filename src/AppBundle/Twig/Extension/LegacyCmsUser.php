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
    public function shouldShowInternalInfo()
    {
        return $this->cmsUserService->shouldShowInternalInfo();
    }

    /**
     * get cms info
     *
     * @return array
     */
    public function getCmsInfo()
    {
        $userinfo['firstname'] = $this->cmsUserService->getUser()['voornaam'];
        $userinfo['server'] = constant('wt_server_name');

        $url = $this->requestStack->getCurrentRequest()->getRequestUri();

        /* @todo: does Symfony provide a function to easilly manipulate url's? */
        if (strpos($url, '?') === false) {
            $url .= '?';
        } else {
            $url .= '&';
        }
        $url .= 'hide_internal=1';

        $userinfo['link_to_hide_internal_info'] = $url;

        return $userinfo;
    }
}
