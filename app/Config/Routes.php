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
// -----------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Modulo de administracion de usuarios y permisos
    $routes->group('admin', ['filter' => 'role:admin,administrador'], function ($routes) {
        $routes->get('usuarios', 'Admin\UsuariosController::index');
        $routes->post('usuarios', 'Admin\UsuariosController::store');
        $routes->post('usuarios/toggle', 'Admin\UsuariosController::toggle');
        $routes->post('usuarios/eliminar', 'Admin\UsuariosController::delete');
        $routes->get('password', 'Admin\PasswordController::index');
        $routes->post('password', 'Admin\PasswordController::update');
    });

    // Compatibilidad con formularios que usan una ruta distinta
    $routes->post('admin/usuarios/toggle', 'Admin\UsuariosController::toggle');
    $routes->post('admin/usuarios/eliminar', 'Admin\UsuariosController::delete');

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
    // TODO (encargado del modulo): agregar create/store/edit/update/delete
});