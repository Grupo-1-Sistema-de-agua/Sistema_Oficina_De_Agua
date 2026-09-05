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
    // TODO (encargado del modulo): agregar create/store/edit/update/delete

    // Modulo: Tarifas
    $routes->get('tarifas', 'Tarifas\TarifasController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete

    // Modulo: Lecturas
    $routes->get('lecturas', 'Lecturas\LecturasController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete + recibo imprimible

    // Modulo: Pagos
    $routes->get('pagos', 'Pagos\PagosController::index');
    $routes->get('pagos/nuevo', 'Pagos\PagosController::create', ['filter' => 'role:secretaria,admin,administrador']);
    $routes->post('pagos', 'Pagos\PagosController::store', ['filter' => 'role:secretaria,admin,administrador']);
    $routes->get('pagos/editar/(:num)', 'Pagos\PagosController::edit/$1', ['filter' => 'role:secretaria,admin,administrador']);
    $routes->post('pagos/actualizar/(:num)', 'Pagos\PagosController::update/$1', ['filter' => 'role:secretaria,admin,administrador']);
    $routes->post('pagos/eliminar', 'Pagos\PagosController::delete', ['filter' => 'role:secretaria,admin,administrador']);
});