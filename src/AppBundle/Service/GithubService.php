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
     * @var Client
     */
    private $redis;

    /**
     * @param Github $githubValidator
     * @param Client $redis
     */
    public function __construct(Github $githubValidator, $redis)
    {
        $this->githubValidator = $githubValidator;
        $this->redis           = $redis;
    }

    /**
     * @param Request $request
     * @return boolean
     */
    public function validate(Request $request)
    {
        return $this->githubValidator->validate($request);
    }

    /**
     * @return \DateTime
     */
    public function markPush()
    {
        $time = new \DateTime();
        $this->redis->set('github-push-received', $time->getTimestamp());

        return $time;
    }

    /**
     * @return \DateTime
     */
    public function unmarkPush()
    {
        $time = $this->redis->get(self::GITHUB_PUSH_RECEIVED);
        $this->redis->unset(self::GITHUB_PUSH_RECEIVED);

        return (new \DateTime())->setTimestamp((int)$time);
    }
}