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
    $routes->group('tipos-servicio', ['filter' => 'role:' . \App\Constants\Roles::ADMINISTRADOR], function ($routes) {
        $routes->get('/', 'Tarifas\TiposServicioController::index');
        $routes->get('crear', 'Tarifas\TiposServicioController::create');
        $routes->post('/', 'Tarifas\TiposServicioController::store');
        $routes->get('(:num)/editar', 'Tarifas\TiposServicioController::edit/$1');
        $routes->put('(:num)', 'Tarifas\TiposServicioController::update/$1');
        $routes->get('(:num)/eliminar', 'Tarifas\TiposServicioController::delete/$1');
    });

    // Modulo: Lecturas
    $routes->get('lecturas', 'Lecturas\LecturasController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete + recibo imprimible

    // Modulo: Pagos
    $routes->get('pagos', 'Pagos\PagosController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete
});