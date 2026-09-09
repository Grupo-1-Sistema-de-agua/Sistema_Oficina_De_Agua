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
    $routes->group('clientes', ['namespace' => 'App\Controllers\Clientes'], function($routes) {
        $routes->get('/', 'ClientesController::index');
        $routes->post('store', 'ClientesController::store');
        $routes->post('update/(:num)', 'ClientesController::update/$1');
        $routes->get('delete/(:num)', 'ClientesController::delete/$1');
    });

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
    // TODO (encargado del modulo): agregar create/store/edit/update/delete

    // Modulo: Recibos
    $routes->group('recibos', ['namespace' => 'App\Controllers\Recibos'], function($routes) {
        $routes->get('/', 'RecibosController::index');
        $routes->post('store', 'RecibosController::store');
        $routes->post('update/(:num)', 'RecibosController::update/$1');
        $routes->get('delete/(:num)', 'RecibosController::delete/$1');
        
        // Rutas corregidas (sin repetir "recibos/" ni el namespace)
        $routes->get('imprimir/(:num)', 'RecibosController::imprimir/$1');
        $routes->get('anular/(:num)', 'RecibosController::anular/$1');  
    });
});