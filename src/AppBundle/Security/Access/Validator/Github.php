<?php
namespace AppBundle\Security\Access\Validator;
use       Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class Github implements AccessValidatorInterface
{
    /**
     * @var string
     */
    private $secret;

    /**
     * @param string $key
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function validate(Request $request)
    {
        return ($request->isMethod('POST') && $this->isSignatureValid($request->headers->get('HTTP_X_HUB_SIGNATURE')));
    }

    /**
     * @param string $signature
     * @param string $payload
     * @return boolean
     */
    public function isSignatureValid($signature, $payload)
    {
        $calculatedSignature = 'sha1=' . hash_hmac(self::GITHUB_HMAC_ALGORITHM, $payload, $this->secret);
        return $signature === $calculatedSignature;
    }
}