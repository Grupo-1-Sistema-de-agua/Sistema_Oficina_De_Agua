<?php

namespace App\Controllers\Tarifas;

use App\Controllers\BaseController;

/**
 * Modulo: Tarifas
 *
 * Punto de partida para este modulo. Reemplacen index() y agreguen
 * los metodos que necesiten (create, store, edit, update, delete, etc.).
 * Las vistas de este modulo van en app/Views/Tarifas/.
 * El Model correspondiente ya existe en app/Models/.
 */
class TarifasController extends BaseController
{
    public function index()
    {
        return view('Tarifas/index');
    }
}
