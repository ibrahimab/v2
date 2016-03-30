<?php
namespace AppBundle\Security\Access\Validator;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class BasicAuth implements AccessValidatorInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->username = getenv('BASIC_AUTH_USERNAME');
        $this->password = getenv('BASIC_AUTH_PASSWORD');
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function validate(Request $request)
    {
        if (!$request->server->has('PHP_AUTH_USER')) {
            return $this->serveLoginScreen();
        } else {
            return $this->check($request->server->get('PHP_AUTH_USER'), $request->server->get('PHP_AUTH_PW'));
        }
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return boolean
     */
    public function check($username, $password)
    {
        return $this->username === $username && $this->password === $password;
    }

    /**
     * @return void
     */
    public function serveLoginScreen()
    {
        header('WWW-Authenticate: Basic realm="Chalet"');
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }
}