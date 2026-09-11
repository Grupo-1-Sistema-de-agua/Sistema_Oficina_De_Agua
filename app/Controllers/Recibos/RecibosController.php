<?php

namespace App\Controllers\Recibos;

use App\Controllers\BaseController;
use App\Models\LecturaModel;
use App\Models\TarifaModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class RecibosController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereRol(['secretaria', 'administrador']);
    }

    /**
     * Listado agrupado por contador: cuantas lecturas tiene, cuantas
     * estan pendientes de pago, y el monto pendiente acumulado.
     * Filtrable por texto (cliente o codigo de contador) y por estado
     * (solo pendientes, o todos los contadores con al menos una lectura).
     */
    public function index()
    {
        $qPendientes = trim((string) $this->request->getGet('q_pendientes'));
        $qPagadas    = trim((string) $this->request->getGet('q_pagadas'));

        // Agrupado por contador. GROUP_CONCAT junta los numeros de recibo
        // de las lecturas pendientes de ese contador en un solo texto
        // separado por comas, en orden de fecha -- la vista se encarga
        // de mostrar solo los primeros y un "+N" si hay muchos.
        $pendientesQuery = db_connect()->table('Tb_Contadores')
            ->select("
                Tb_Contadores.id AS contador_id,
                Tb_Contadores.codigo_fisico,
                Tb_Clientes.nombre AS cliente_nombre,
                COUNT(Tb_Lecturas.id) AS lecturas_pendientes,
                SUM(Tb_Lecturas.monto_base + Tb_Lecturas.monto_exceso) AS monto_pendiente,
                GROUP_CONCAT(Tb_Lecturas.numero_recibo ORDER BY Tb_Lecturas.fecha ASC SEPARATOR ',') AS recibos_pendientes
            ", false)
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->join('Tb_Lecturas', 'Tb_Lecturas.contador_id = Tb_Contadores.id')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id_activa = Tb_Lecturas.id', 'left')
            ->where('Tb_Pagos.id', null)
            ->groupBy('Tb_Contadores.id');

        if ($qPendientes !== '') {
            $pendientesQuery->groupStart()
                ->like('Tb_Clientes.nombre', $qPendientes)
                ->orLike('Tb_Lecturas.numero_recibo', $qPendientes)
            ->groupEnd();
        }

        $contadoresPendientes = $pendientesQuery->orderBy('Tb_Clientes.nombre', 'ASC')->get()->getResultArray();

        // Pagadas SI queda individual, una fila por lectura -- cada una
        // tiene su propio comprobante que ver aparte.
        $pagadasQuery = (new LecturaModel())
            ->select('Tb_Lecturas.id, Tb_Lecturas.numero_recibo, Tb_Lecturas.fecha, Tb_Lecturas.monto_base, Tb_Lecturas.monto_exceso, Tb_Contadores.codigo_fisico, Tb_Clientes.nombre AS cliente_nombre, Tb_Pagos.fecha_pago, Tb_Metodos_Pago.nombre AS metodo_nombre')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id_activa = Tb_Lecturas.id')
            ->join('Tb_Metodos_Pago', 'Tb_Metodos_Pago.id = Tb_Pagos.metodo_id')
            ->join('Tb_Contadores', 'Tb_Contadores.id = Tb_Lecturas.contador_id')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->orderBy('Tb_Pagos.fecha_pago', 'DESC');

        if ($qPagadas !== '') {
            $pagadasQuery->groupStart()
                ->like('Tb_Clientes.nombre', $qPagadas)
                ->orLike('Tb_Lecturas.numero_recibo', $qPagadas)
            ->groupEnd();
        }

        $lecturasPagadas = $pagadasQuery->findAll();

        return view('recibos/index', [
            'contadoresPendientes' => $contadoresPendientes,
            'lecturasPagadas'      => $lecturasPagadas,
            'qPendientes'          => $qPendientes,
            'qPagadas'             => $qPagadas,
        ]);
    }

    /**
     * Documento imprimible de un contador: lista TODAS sus lecturas
     * (pagadas y pendientes, cada una con su estado), y un total general
     * solo de lo pendiente. Asi sirve tanto para el caso normal (varias
     * lecturas sin pagar) como para reimprimir el historial completo de
     * un cliente que ya esta al dia.
     */
    public function imprimir(int $contadorId)
    {
        $contador = db_connect()->table('Tb_Contadores')
            ->select('Tb_Contadores.*, Tb_Clientes.nombre AS cliente_nombre, Tb_Clientes.direccion_principal, Tb_Clientes.telefono, Tb_Sectores.nombre AS sector_nombre, Tb_Tipos_Servicios.nombre AS tipo_nombre')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->join('Tb_Sectores', 'Tb_Sectores.id = Tb_Contadores.sector_id')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->where('Tb_Contadores.id', $contadorId)
            ->get()->getRowArray();

        if (! $contador) {
            flash_set('error', 'Contador no encontrado.');
            return redirect()->to('/recibos');
        }

        // El recibo solo incluye lo que todavia se debe, no el historial
        // completo -- por eso solo se traen lecturas sin pago activo.
        $lecturas = (new LecturaModel())
            ->select('Tb_Lecturas.*, Tb_Tarifas.precio AS tarifa_precio')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id_activa = Tb_Lecturas.id', 'left')
            ->join('Tb_Tarifas', 'Tb_Tarifas.id = Tb_Lecturas.tarifa_base_id')
            ->where('Tb_Lecturas.contador_id', $contadorId)
            ->where('Tb_Pagos.id', null)
            ->orderBy('Tb_Lecturas.fecha', 'ASC')
            ->findAll();

        if (empty($lecturas)) {
            flash_set('error', 'Este contador esta al dia, no hay nada pendiente que imprimir.');
            return redirect()->to('/recibos');
        }

        $totalPendiente = 0.0;
        foreach ($lecturas as $lectura) {
            $totalPendiente += (float) $lectura['monto_base'] + (float) $lectura['monto_exceso'];
        }

        return view('recibos/ticket', [
            'contador'       => $contador,
            'lecturas'       => $lecturas,
            'totalPendiente' => $totalPendiente,
            'fechaEmision'   => date('Y-m-d H:i:s'),
        ]);
    }
}