<?php

namespace App\Controllers\Clientes;

use App\Controllers\BaseController;

/**
 * Modulo: Clientes
 *
 * Punto de partida para este modulo. Reemplacen index() y agreguen
 * los metodos que necesiten (create, store, edit, update, delete, etc.).
 * Las vistas de este modulo van en app/Views/Clientes/.
 * El Model correspondiente ya existe en app/Models/.
 */
class ClientesController extends BaseController
{
    public function index()
    {
        return view('Clientes/index');
    }
}
