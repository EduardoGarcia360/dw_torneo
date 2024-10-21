<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->resource('equipos', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('jugadores', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('jornadas', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('goles', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('incidencias', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('usuarios', ['placeholder' => '(:num)', 'except' => 'show']);