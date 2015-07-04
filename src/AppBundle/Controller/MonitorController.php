<?php
namespace AppBundle\Controller;
use       AppBundle\Annotation\Breadcrumb;
use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;
use       Symfony\Component\HttpFoundation\JsonResponse;

/**
 * MonitorController
 *
 * This controller handles the montoring of services
 *
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.1
 */
class MonitorController extends Controller
{
    /**
     * @Route("/monitor", name="monitor")
     */
    public function index()
    {
        $monitorService = $this->get('app.monitor');

        return new JsonResponse([

            'server'    => '--to-be-implemented--',
            'timestamp' => time(),
            'services'  => [

                'httpd'    => $monitorService->httpd(),
                'database' => $monitorService->database(),
                'redis'    => $monitorService->redis(),
                'mongo'    => $monitorService->mongo(),
            ],
        ]);
    }

    /**
     * @Route("/monitor/httpd", name="monitor_httpd")
     */
    public function httpd()
    {
        return new JsonResponse([

            'server'    => '--to-be-implemented--',
            'timestamp' => time(),
            'status'    => $this->get('app.monitor')->httpd(),
        ]);
    }

    /**
     * @Route("/monitor/database", name="monitor_database")
     */
    public function database()
    {
        return new JsonResponse([

            'server'    => '--to-be-implemented--',
            'timestamp' => time(),
            'status'    => $this->get('app.monitor')->database(),
        ]);
    }

    /**
     * @Route("/monitor/redis", name="monitor_redis")
     */
    public function redis()
    {
        return new JsonResponse([

            'server'    => '--to-be-implemented--',
            'timestamp' => time(),
            'status'    => $this->get('app.monitor')->redis(),
        ]);
    }

    /**
     * @Route("/monitor/mongo", name="monitor_mongo")
     */
    public function mongo()
    {
        return new JsonResponse([

            'server'    => '--to-be-implemented--',
            'timestamp' => time(),
            'status'    => $this->get('app.monitor')->mongo(),
        ]);
    }
}