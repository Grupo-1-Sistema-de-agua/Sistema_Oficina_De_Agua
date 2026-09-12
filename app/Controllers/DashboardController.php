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
            default         => $this->cerrarSesionInvalida(),
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


    private function estadoDeCuenta(): array
    {
        $q = trim((string) $this->request->getGet('q_cuenta'));

        $query = db_connect()->table('Tb_Clientes')
            ->select('Tb_Clientes.id, Tb_Clientes.nombre, Tb_Clientes.telefono, COUNT(DISTINCT CASE WHEN Tb_Lecturas.id IS NOT NULL AND Tb_Pagos.id IS NULL THEN Tb_Lecturas.id END) AS lecturas_pendientes', false)
            ->join('Tb_Contadores', 'Tb_Contadores.cliente_id = Tb_Clientes.id', 'left')
            ->join('Tb_Lecturas', 'Tb_Lecturas.contador_id = Tb_Contadores.id', 'left')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id_activa = Tb_Lecturas.id', 'left')
            ->groupBy('Tb_Clientes.id')
            ->orderBy('Tb_Clientes.nombre', 'ASC');

        if ($q !== '') {
            $query->like('Tb_Clientes.nombre', $q);
        }

        return [
            'estadosCuenta' => $query->get()->getResultArray(),
            'qCuenta'       => $q,
        ];
    }

    private function dashboardAdministrador()
    {
        return view('dashboard/administrador', array_merge(
            ['nombre' => $_SESSION['nombre'] ?? null],
            $this->totalesGenerales(),
            $this->estadoDeCuenta()
        ));
    }

    private function dashboardSecretaria()
    {
        return view('dashboard/secretaria', array_merge(
            ['nombre' => $_SESSION['nombre'] ?? null],
            $this->totalesGenerales(),
            $this->estadoDeCuenta()
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