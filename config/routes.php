<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'BEdita/DevTools',
    [
        'path' => '/tools',
    ],
    function (RouteBuilder $routes) {
        // OpenAPI 3 spec in YAML format.
        $routes->connect(
            '/open-api',
            ['controller' => 'Tools', 'action' => 'openApi', 'method' => 'GET']
        );

        $routes->fallbacks(DashedRoute::class);
    }
);
