<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// -----------------------------------------------------------------
// Publicas (sin sesion)
// -----------------------------------------------------------------
$routes->get('/', 'Auth\AuthController::login');
$routes->get('login', 'Auth\AuthController::login');
$routes->post('login', 'Auth\AuthController::procesarLogin');
$routes->get('logout', 'Auth\AuthController::logout');

// -----------------------------------------------------------------
// Protegidas (exigen sesion iniciada -> filtro 'auth')
// Para restringir ademas por rol, agreguen ',role:administrador'
// (o los roles que corresponda) usando las constantes de
// App\Constants\Roles.
// -----------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Modulo: Clientes
    $routes->get('clientes', 'Clientes\ClientesController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete

    // Modulo: Contadores
    $routes->get('contadores', 'Contadores\ContadoresController::index');
   // Modulo: Contadores (Secretaria y Administrador)
    $routes->get('contadores/nuevo', 'Contadores\ContadoresController::nuevo');
    $routes->post('contadores', 'Contadores\ContadoresController::crear');
    $routes->get('contadores/editar/(:num)', 'Contadores\ContadoresController::editar/$1');
    $routes->post('contadores/actualizar/(:num)', 'Contadores\ContadoresController::actualizar/$1');
    $routes->post('contadores/eliminar/(:num)', 'Contadores\ContadoresController::eliminar/$1');

    // Modulo: Tarifas
    $routes->get('tarifas', 'Tarifas\TarifasController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete

    // Modulo: Lecturas
    $routes->get('lecturas', 'Lecturas\LecturasController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete + recibo imprimible

    // Modulo: Pagos
    $routes->get('pagos', 'Pagos\PagosController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete
});