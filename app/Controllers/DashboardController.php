<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class DashboardController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereLogin();
    }

    public function index()
    {
        $rol = $_SESSION['rol'] ?? null;

        return match ($rol) {
            'administrador' => $this->dashboardAdministrador(),
            'secretaria'    => $this->dashboardSecretaria(),
            'lector'        => $this->dashboardLector(),
            default         => view('dashboard/index', ['nombre' => $_SESSION['nombre'] ?? null, 'rol' => $rol]),
        };
    }

    /**
     * SDGA-55: totales generales, comunes a los tres roles.
     */
    private function totalesGenerales(): array
    {
        $db = db_connect();

        return [
            'totalClientes'     => $db->table('Tb_Clientes')->countAllResults(),
            'contadoresActivos' => $db->table('Tb_Contadores')->where('activo', 1)->countAllResults(),
            'lecturasDelMes'    => $db->table('Tb_Lecturas')
                ->where('fecha >=', date('Y-m-01 00:00:00'))
                ->where('fecha <', date('Y-m-01 00:00:00', strtotime('+1 month')))
                ->countAllResults(),
        ];
    }

    private function dashboardAdministrador()
    {
        return view('dashboard/administrador', array_merge(
            ['nombre' => $_SESSION['nombre'] ?? null],
            $this->totalesGenerales()
        ));
    }

    private function dashboardSecretaria()
    {
        return view('dashboard/secretaria', array_merge(
            ['nombre' => $_SESSION['nombre'] ?? null],
            $this->totalesGenerales()
        ));
    }

    private function dashboardLector()
    {
        return view('dashboard/lector', array_merge(
            ['nombre' => $_SESSION['nombre'] ?? null],
            $this->totalesGenerales()
        ));
    }
}