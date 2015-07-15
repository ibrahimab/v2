<?php
namespace AppBundle\Controller;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;
use       Symfony\Component\HttpFoundation\Response;
use       Symfony\Component\Filesystem\Filesystem;
use       Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
class HookController extends Controller
{
    /**
     * @Route(path="/github/push", name="github_push")
     */
    public function push(Request $request)
    {
        $logger = $this->get('monolog.logger.controller');
        $logger->info('Someone requested a push request');

        $github = $this->get('app.security.access.validator.github');

        $fs->touch($this->container->getParameter('github_dir') . '/push.txt');exit;
        if (true === $valid) {

            $logger->info('Push request was a valid request from github');

            $fs = new Filesystem();
            $fs->touch($this->container->getParameter('github_dir') . '/push.txt');

        } else {
            $logger->error('Push request was not a valid request from github');
        }

        return new Response();
    }
}