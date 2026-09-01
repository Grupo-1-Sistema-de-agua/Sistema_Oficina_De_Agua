<?php

namespace App\Controllers\Lecturas;

use App\Controllers\BaseController;

/**
 * Modulo: Lecturas
 *
 * Punto de partida para este modulo. Reemplacen index() y agreguen
 * los metodos que necesiten (create, store, edit, update, delete, etc.).
 * Las vistas de este modulo van en app/Views/Lecturas/.
 * El Model correspondiente ya existe en app/Models/.
 */
class LecturasController extends BaseController
{
    public function index()
    {
        return view('Lecturas/index');
    }
}
