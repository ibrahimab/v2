<?php
/**
 * @author  Ibrahim Abdullah <ibrahim@chalet.nl>
 * @package Chalet
 * @version 0.0.5
 * @since   0.0.5
 */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Dotenv\Dotenv;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
Debug::enable();

$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

error_reporting(E_ALL ^ E_NOTICE);

$request  = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);