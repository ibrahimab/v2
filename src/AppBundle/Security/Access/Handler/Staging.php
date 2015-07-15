<?php
namespace AppBundle\Security\Access\Handler;
use       Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Staging implements AccessHandlerInterface
{
    /**
     * @var array
     * @static
     */
    private static $_ALLOWED_IPS = ['62.194.208.250', '213.125.152.154', '31.223.173.113'];

    /**
     * @param Request $request
     * @return boolean
     */
    public function handle(Request $request)
    {
        return in_array($request->getClientIp(), self::$_ALLOWED_IPS);
    }
}