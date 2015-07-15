<?php
/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
use AppBundle\Security\Access\BootstrapAccess;
use AppBundle\Security\Access\Handler\Development;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

error_reporting(E_ALL ^ E_NOTICE);

$request = Request::createFromGlobals();

$bootstrapAccess = new BootstrapAccess(new Development, $request);
$bootstrapAccess->check();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);