<?php
/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
use AppBundle\Security\Access\BootstrapAccess;
use AppBundle\Security\Access\Handler\Staging;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('stag', false);
$kernel->loadClassCache();

$request = Request::createFromGlobals();

$bootstrapAccess = new BootstrapAccess(new Staging, $request);
$bootstrapAccess->check();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
