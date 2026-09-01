<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    /**
     * Landing page despues del login. Cada quien puede ir agregando
     * aqui los widgets/resumen que le correspondan a su modulo, o
     * dejarla como esta y enlazar desde el sidebar del layout.
     */
    public function index()
    {
        return view('dashboard/index', [
            'nombre' => session()->get('usuario_nombre'),
            'rol'    => session()->get('usuario_rol'),
        ]);
    }
}