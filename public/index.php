<?php
date_default_timezone_set('America/Sao_Paulo');
//error_reporting(E_ALL);
error_reporting(0);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new Slim\App([
    "settings"  => [
        "determineRouteBeforeAppMiddleware" => true,
    ]
]);

$app->add(function($request, $response, $next) {
    $route = $request->getAttribute("route");
    $response = $next($request, $response);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader("Access-Control-Allow-Methods", 'GET, POST, PUT, DELETE')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, apiKey');
});

require_once('../app/config/config.php');
require_once('../app/api/util/util.php');
require_once('../app/middleware/middleware.php');
require_once('../app/api/worldometers.php');

$app->run();
