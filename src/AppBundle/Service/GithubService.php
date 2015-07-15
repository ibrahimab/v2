<?php
namespace AppBundle\Service;
use       AppBundle\Security\Access\Validator\Github;
use       Symfony\Component\HttpFoundation\Request;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class GithubService
{
    /**
     * @var Github
     */
    private $githubValidator;

    /**
     * @var Redis
     */
    private $redis;

    public function __construct(Github $githubValidator, $redis)
    {
        dump(get_class($redis));exit;
        $this->githubValidator = $githubValidator;
        $this->redis           = $redis;
    }

    public function validate(Request $request)
    {
        # code...
    }
}