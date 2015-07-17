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
     * @const string
     */
    const GITHUB_PUSH_RECEIVED    = 'github:push:received';
    
    /**
     * @const string
     */
    const GITHUB_PULL_IN_PROGRESS = 'github:pull:in:progress';
    
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
    public function pushReceived()
    {
        $time = new \DateTime();
        $this->redis->set(self::GITHUB_PUSH_RECEIVED, $time->getTimestamp());

        return $time;
    }
    
    /**
     * @return \DateTime
     */
    public function startPulling()
    {
        $time = new \DateTime();
        $this->redis->set(self::GITHUB_PULL_IN_PROGRESS, $time->getTimestamp());
        
        return $time;
    }
    
    /**
     * @return \DateTime
     */
    public function getStartPulling()
    {
        return $this->redis->get(self::GITHUB_PULL_IN_PROGRESS);
    }

    /**
     * @return \DateTime
     */
    public function pullCompleted()
    {
        $this->redis->del(self::GITHUB_PUSH_RECEIVED);
        $this->redis->del(self::GITHUB_PULL_IN_PROGRESS);

        return (new \DateTime())->setTimestamp((int)$this->redis->get(self::GITHUB_PUSH_RECEIVED));
    }
    
    /**
     * @return boolean
     */
    public function pullAvailable()
    {
        return (bool)$this->redis->exists(self::GITHUB_PUSH_RECEIVED);
    }
    
    /**
     * @return boolean
     */
    public function isPulling()
    {
        return (bool)$this->redis->exists(self::GITHUB_PULL_IN_PROGRESS);
    }
}