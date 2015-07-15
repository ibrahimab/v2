<?php
namespace AppBundle\Security\Access\Handler;
use       AppBundle\Security\Access\Validator\AccessValidatorInterface;
use       AppBundle\Security\Access\Validator\Ip;
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
     * @var AccessValidatorInterface[]
     */
    private $validators = [];

    /**
     * Constructor - adding validators
     *
     * @param Ip $ipValidator
     */
    public function __construct(Ip $ip)
    {
        $this->add($ip);
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function handle(Request $request)
    {
        foreach ($this->validators as $validator) {

            if (true === $validator->validate($request)) {
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