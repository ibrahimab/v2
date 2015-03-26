<?php
namespace AppBundle\Controller;

use       Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use       Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Javascript controller, this controller will use the javascript service to generate a global javascript object
 */
class JavascriptController extends Controller
{
    /**
     * @Route(path="/js/global.js", name="javacript")
     */
    public function show()
    {
        return $this->render();
    }
}