<?php

namespace App\Controllers\Contadores;

use App\Controllers\BaseController;

/**
 * Modulo: Contadores
 *
 * Punto de partida para este modulo. Reemplacen index() y agreguen
 * los metodos que necesiten (create, store, edit, update, delete, etc.).
 * Las vistas de este modulo van en app/Views/Contadores/.
 * El Model correspondiente ya existe en app/Models/.
 */
class ContadoresController extends BaseController
{
    public function index()
    {
        return view('Contadores/index');
    }
}
