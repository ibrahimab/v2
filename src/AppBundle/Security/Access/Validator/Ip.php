<?php
namespace AppBundle\Security\Access\Validator;
use       Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Ip implements AccessValidatorInterface
{
    /**
     * @var array
     */
    private $allowedIps;

    /**
     * @param array $allowedIps
     */
    public function __construct($allowedIps)
    {
        $this->allowedIps = $allowedIps;
    }

    /**
     * @param Request $request
     */
    public function validate(Request $request)
    {
        return in_array($request->getClientIp(), $this->allowedIps);
    }
}