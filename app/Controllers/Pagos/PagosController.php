<?php

namespace App\Controllers\Pagos;

use App\Controllers\BaseController;

/**
 * Modulo: Pagos
 *
 * Punto de partida para este modulo. Reemplacen index() y agreguen
 * los metodos que necesiten (create, store, edit, update, delete, etc.).
 * Las vistas de este modulo van en app/Views/Pagos/.
 * El Model correspondiente ya existe en app/Models/.
 */
class PagosController extends BaseController
{
    public function index()
    {
        return view('Pagos/index');
    }
}
