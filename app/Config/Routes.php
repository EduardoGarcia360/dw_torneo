<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/inicio', 'Home::inicio');
$routes->post('/login', 'Home::login');
$routes->get('/salir', 'Home::salir');
$routes->resource('equipos', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('jugadores', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('jornadas', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('goles', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('incidencias', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->resource('usuarios', ['placeholder' => '(:num)', 'except' => 'show']);
$routes->get('reportea', 'Reportea::index');
$routes->get('reportea/generarReportePdf', 'Reportea::generarReportePdf');
$routes->get('reporteb', 'Reporteb::index');
$routes->get('reporteb/generar_pdf', 'Reporteb::generar_pdf');
$routes->get('reportec', 'Reportec::index');
$routes->get('reportec/generar_pdf', 'Reportec::generar_pdf');
$routes->get('reported', 'Reported::listarJugadores');
$routes->get('reported/generar_pdf', 'Reported::generar_pdf');