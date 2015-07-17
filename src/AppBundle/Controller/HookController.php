<?php
namespace AppBundle\Controller;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\Request;
use       Symfony\Component\HttpFoundation\JsonResponse;
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
        $github = $this->get('app.github');
        
        $logger->info('Someone requested a push request');

        if (true === $github->validate($request)) {

            $logger->info('Push request was a valid request from github');
            $github->markPush();

            $type    = 'success';
            $message = 'Github push request successfully processed';

        } else {

            $logger->error('Push request was not a valid request from github');

            $type    = 'error';
            $message = 'Github push request was not a valid request';
        }

        return new JsonResponse([

            'type'    => $type,
            'message' => $message,
        ]);
    }
}