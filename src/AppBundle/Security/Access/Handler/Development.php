<?php
namespace AppBundle\Security\Access\Handler;
use       Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Development implements AccessHandlerInterface
{
    /**
     * @var array
     * @static
     */
    private static $_ALLOWED_IPS = ['192.168.33.1'];
    /**
     * @param Request $request
     * @return boolean
     */
    public function handle(Request $request)
    {
        return in_array($request->getClientIp(), self::$_ALLOWED_IPS);
    }
}