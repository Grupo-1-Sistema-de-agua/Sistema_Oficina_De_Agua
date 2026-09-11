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
// Protegidas. El control de acceso (sesion iniciada y, donde aplica,
// rol permitido) ya no se hace con Filters de CodeIgniter: cada
// controlador llama a $this->requiereLogin() o $this->requiereRol([...])
// (definidos en BaseController) al inicio de sus metodos protegidos.
// -----------------------------------------------------------------
$routes->get('dashboard', 'DashboardController::index');

// Modulo de administracion de usuarios y permisos
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {
    $routes->get('usuarios', 'UsuariosController::index');
    $routes->get('usuarios/nuevo', 'UsuariosController::nuevo');
    $routes->post('usuarios', 'UsuariosController::store');
    $routes->post('usuarios/toggle', 'UsuariosController::toggle');
    $routes->post('usuarios/eliminar', 'UsuariosController::delete');
    $routes->get('usuarios/(:num)/password', 'PasswordController::edit/$1');
    $routes->post('usuarios/(:num)/password', 'PasswordController::update/$1');
});

// Modulo: Clientes
$routes->group('clientes', ['namespace' => 'App\Controllers\Clientes'], function ($routes) {
    $routes->get('/', 'ClientesController::index');
    $routes->post('store', 'ClientesController::store');
    $routes->post('update/(:num)', 'ClientesController::update/$1');
    $routes->get('delete/(:num)', 'ClientesController::delete/$1');
});

// Modulo: Contadores
$routes->group('contadores', ['namespace' => 'App\Controllers\Contadores'], function ($routes) {
    $routes->get('/', 'ContadoresController::index');
    $routes->get('nuevo', 'ContadoresController::nuevo');
    $routes->post('/', 'ContadoresController::crear');
    $routes->get('editar/(:num)', 'ContadoresController::editar/$1');
    $routes->post('actualizar/(:num)', 'ContadoresController::actualizar/$1');
    $routes->post('eliminar/(:num)', 'ContadoresController::eliminar/$1');
    $routes->get('ver/(:num)', 'ContadoresController::ver/$1');
});

// Modulo: Tarifas
$routes->group('tarifas', ['namespace' => 'App\Controllers\Tarifas'], function ($routes) {
    $routes->get('/', 'TarifasController::index');
    $routes->get('crear', 'TarifasController::create');
    $routes->post('/', 'TarifasController::store');
    $routes->post('(:num)/anular', 'TarifasController::anular/$1');
});

// Modulo: Tipos de Servicio (parte de Tarifas, ruta propia)
$routes->group('tipos-servicio', ['namespace' => 'App\Controllers\Tarifas'], function ($routes) {
    $routes->get('/', 'TiposServicioController::index');
    $routes->get('crear', 'TiposServicioController::create');
    $routes->post('/', 'TiposServicioController::store');
    $routes->get('(:num)/editar', 'TiposServicioController::edit/$1');
    $routes->put('(:num)', 'TiposServicioController::update/$1');
    $routes->get('(:num)/eliminar', 'TiposServicioController::delete/$1');
});

// Modulo: Lecturas
$routes->group('lecturas', ['namespace' => 'App\Controllers\Lecturas'], function ($routes) {
    $routes->get('/', 'LecturasController::index');
    $routes->get('nueva/(:num)', 'LecturasController::nueva/$1');
    $routes->post('guardar', 'LecturasController::guardar');
    $routes->get('editar/(:num)', 'LecturasController::editar/$1');
    $routes->post('actualizar', 'LecturasController::actualizar');
});

// Modulo: Pagos
$routes->group('pagos', ['namespace' => 'App\Controllers\Pagos'], function ($routes) {
    $routes->get('/', 'PagosController::index');
    $routes->get('nuevo', 'PagosController::create');
    $routes->post('/', 'PagosController::store');
    $routes->get('editar/(:num)', 'PagosController::edit/$1');
    $routes->post('actualizar/(:num)', 'PagosController::update/$1');
    $routes->post('eliminar', 'PagosController::delete');
});

// Modulo: Recibos
$routes->group('recibos', ['namespace' => 'App\Controllers\Recibos'], function ($routes) {
    $routes->get('/', 'RecibosController::index');
    $routes->post('store', 'RecibosController::store');
    $routes->post('update/(:num)', 'RecibosController::update/$1');
    $routes->get('delete/(:num)', 'RecibosController::delete/$1');
    $routes->get('imprimir/(:num)', 'RecibosController::imprimir/$1');
    $routes->get('anular/(:num)', 'RecibosController::anular/$1');
});