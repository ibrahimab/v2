<?php
namespace AppBundle\Security\Access\Validator;
use       Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
interface AccessValidatorInterface
{
    /**
     * @param Request $request
     * @return boolean
     */
    public function validate(Request $request);
}