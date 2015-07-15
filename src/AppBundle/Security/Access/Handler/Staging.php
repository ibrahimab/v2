<?php
namespace AppBundle\Security\Access\Handler;
use       AppBundle\Security\Access\Validator\AccessValidatorInterface;
use       AppBundle\Security\Access\Validator\Ip;
use       AppBundle\Security\Access\Validator\Github;
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
     * @var AccessValidatorInterface[]
     */
    private $validators = [];

    /**
     * @var array
     * @static
     */
    private static $_ALLOWED_IPS = ['62.194.208.250', '213.125.152.154', '31.223.173.113'];

    /**
     * Adding github validator in constructor
     */
    public function __construct(Ip $ip, Github $github)
    {
        $this->add($ip);
        $this->add($github);
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function handle(Request $request)
    {
        foreach ($this->validators as $validator) {

            if (true === $validator->validate()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param AccessValidatorInterface $validator
     */
    public function add(AccessValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }
}