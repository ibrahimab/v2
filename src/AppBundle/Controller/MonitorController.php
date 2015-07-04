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
            'timestamp' => (new \DateTime())->getTimestamp(),
            'services'  => [

                'httpd'    => true,
                'database' => $monitorService->database(),
                'redis'    => $monitorService->redis(),
                'mongo'    => $monitorService->mongo(),
            ],
        ]);
    }
}