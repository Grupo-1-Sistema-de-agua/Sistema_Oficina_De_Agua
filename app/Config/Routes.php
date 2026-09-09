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
   // Modulo: Contadores (Secretaria y Administrador)
    $routes->get('contadores/nuevo', 'Contadores\ContadoresController::nuevo');
    $routes->post('contadores', 'Contadores\ContadoresController::crear');
    $routes->get('contadores/editar/(:num)', 'Contadores\ContadoresController::editar/$1');
    $routes->post('contadores/actualizar/(:num)', 'Contadores\ContadoresController::actualizar/$1');
    $routes->post('contadores/eliminar/(:num)', 'Contadores\ContadoresController::eliminar/$1');
    $routes->get('contadores/ver/(:num)', 'Contadores\ContadoresController::ver/$1');

    // Modulo: Tarifas
    $routes->get('tarifas', 'Tarifas\TarifasController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete

    // Modulo: Lecturas
    $routes->get('lecturas', 'Lecturas\LecturasController::index');
    // TODO (encargado del modulo): agregar create/store/edit/update/delete + recibo imprimible
    // Modulo: Lecturas
    $routes->get('lecturas/nueva/(:num)', 'Lecturas\LecturasController::nueva/$1');
    $routes->post('lecturas/guardar', 'Lecturas\LecturasController::guardar');
    // NUEVO: edicion de la lectura vigente del mes (editar recibe el id de la
    // lectura; actualizar guarda los cambios y recalcula consumo y montos)
    $routes->get('lecturas/editar/(:num)', 'Lecturas\LecturasController::editar/$1');
    $routes->post('lecturas/actualizar', 'Lecturas\LecturasController::actualizar');
    
    // Modulo: Pagos
    $routes->get('pagos', 'Pagos\PagosController::index');
    $routes->get('pagos/nuevo', 'Pagos\PagosController::create', ['filter' => 'role:secretaria,admin,administrador']);
    $routes->post('pagos', 'Pagos\PagosController::store', ['filter' => 'role:secretaria,admin,administrador']);
    $routes->get('pagos/editar/(:num)', 'Pagos\PagosController::edit/$1', ['filter' => 'role:secretaria,admin,administrador']);
    $routes->post('pagos/actualizar/(:num)', 'Pagos\PagosController::update/$1', ['filter' => 'role:secretaria,admin,administrador']);
    $routes->post('pagos/eliminar', 'Pagos\PagosController::delete', ['filter' => 'role:secretaria,admin,administrador']);
});