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
        $q      = trim((string) $this->request->getGet('q'));
        $estado = $this->request->getGet('estado') ?: 'pendientes';

        $query = db_connect()->table('Tb_Contadores')
            ->select('
                Tb_Contadores.id AS contador_id,
                Tb_Contadores.codigo_fisico,
                Tb_Clientes.nombre AS cliente_nombre,
                COUNT(Tb_Lecturas.id) AS total_lecturas,
                SUM(CASE WHEN Tb_Pagos.id IS NULL THEN 1 ELSE 0 END) AS lecturas_pendientes,
                SUM(CASE WHEN Tb_Pagos.id IS NULL THEN Tb_Lecturas.monto_base + Tb_Lecturas.monto_exceso ELSE 0 END) AS monto_pendiente
            ', false)
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->join('Tb_Lecturas', 'Tb_Lecturas.contador_id = Tb_Contadores.id')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id_activa = Tb_Lecturas.id', 'left')
            ->groupBy('Tb_Contadores.id');

        if ($q !== '') {
            $query->groupStart()
                ->like('Tb_Clientes.nombre', $q)
                ->orLike('Tb_Contadores.codigo_fisico', $q)
            ->groupEnd();
        }

        if ($estado === 'pendientes') {
            $query->having('lecturas_pendientes >', 0);
        }

        $contadores = $query->orderBy('Tb_Clientes.nombre', 'ASC')->get()->getResultArray();

        return view('recibos/index', [
            'contadores' => $contadores,
            'q'          => $q,
            'estado'     => $estado,
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

        $lecturas = (new LecturaModel())
            ->select('Tb_Lecturas.*, Tb_Pagos.id AS pago_id, Tb_Pagos.fecha_pago, Tb_Pagos.metodo_id, Tb_Metodos_Pago.nombre AS metodo_nombre, Tb_Tarifas.precio AS tarifa_precio')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id_activa = Tb_Lecturas.id', 'left')
            ->join('Tb_Metodos_Pago', 'Tb_Metodos_Pago.id = Tb_Pagos.metodo_id', 'left')
            ->join('Tb_Tarifas', 'Tb_Tarifas.id = Tb_Lecturas.tarifa_base_id')
            ->where('Tb_Lecturas.contador_id', $contadorId)
            ->orderBy('Tb_Lecturas.fecha', 'ASC')
            ->findAll();

        $totalPendiente = 0.0;
        foreach ($lecturas as $lectura) {
            if (! $lectura['pago_id']) {
                $totalPendiente += (float) $lectura['monto_base'] + (float) $lectura['monto_exceso'];
            }
        }

        return view('recibos/ticket', [
            'contador'       => $contador,
            'lecturas'       => $lecturas,
            'totalPendiente' => $totalPendiente,
            'fechaEmision'   => date('Y-m-d H:i:s'),
        ]);
    }
}