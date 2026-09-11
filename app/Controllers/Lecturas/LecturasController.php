<?php

namespace App\Controllers\Lecturas;

use App\Controllers\BaseController;
use App\Models\ContadorModel;
use App\Models\LecturaModel;
use App\Models\TarifaModel;
use App\Models\SectorModel;
use App\Models\TipoServicioModel;
use App\Models\ClienteModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Controlador del modulo de Lecturas.
 *
 * CAMBIOS RECIENTES implementados por fases:
 * - index(): se agrego busqueda por numero de contador/cliente ($q) y se
 *   envia a la vista el mapa de lecturas del mes actual ("lecturasMes")
 *   para saber si cada contador ya tiene lectura este mes.
 * - nueva(): la fecha ya NO se pide al usuario; se usa la fecha/hora actual
 *   del sistema (campo $fecha automatico).
 * - guardar(): se aplica la regla de UNA lectura por contador por mes de
 *   calendario (rechaza si ya existe una en el mes).
 * - editar()/actualizar(): nuevos metodos para corregir la lectura vigente
 *   del mes; se recalcula consumo y montos, y se bloquea la edicion si la
 *   lectura es de un mes anterior o si ya fue pagada.
 */
class LecturasController extends BaseController
{
    private ContadorModel $contadores;
    private LecturaModel $lecturas;
    private TarifaModel $tarifas;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereRol(['lector', 'administrador']);
    }

    public function __construct()
    {
        $this->contadores = model('ContadorModel');
        $this->lecturas   = model('LecturaModel');
        $this->tarifas    = model('TarifaModel');
    }

    /**
     * Lista de contadores activos pendientes de lectura.
     * Filtrable por sector (zona/barrio) y por numero de contador o cliente.
     * Se agrego $lecturasMes para mostrar el estado de la lectura del mes.
     */
    public function index()
    {
        $sectorId    = $this->request->getGet('sector');
        $q           = $this->request->getGet('q');
        $data['titulo']   = 'Contadores pendientes de lectura';
        $data['sectores'] = model('SectorModel')->findAll();
        $data['sectorSeleccionado'] = $sectorId;
        $data['q']        = $q;
        $data['pendientes'] = $this->contadores->pendientesLectura(
            $sectorId ? (int) $sectorId : null,
            $q ?: null
        );

        // Mapa contador_id => id de la lectura del mes (si ya se registro)
        $ids = array_column($data['pendientes'], 'id');
        $data['lecturasMes'] = $this->lecturas->lecturaDelMesActual($ids);

        return view('Lecturas/index', $data);
    }

    /**
     * Formulario para registrar una lectura de un contador.
     * Muestra informacion del usuario, tipo de servicio, tarifa vigente y la
     * ultima lectura. Tambien envia la fecha actual automatica ($fecha) para
     * que la vista la muestre y la use la regla de una lectura por mes.
     */
    public function nueva($contadorId = null)
    {
        $contador = $this->contadores
            ->select('Tb_Contadores.*, Tb_Clientes.nombre as cliente_nombre, Tb_Sectores.nombre as sector_nombre, Tb_Tipos_Servicios.nombre as tipo_nombre, Tb_Tipos_Servicios.volumen_incluido_litros')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->join('Tb_Sectores', 'Tb_Sectores.id = Tb_Contadores.sector_id')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->find($contadorId);

        if (! $contador) {
            flash_set('error', 'Contador no encontrado.');
            return redirect()->to('/lecturas');
        }

        if ((int) $contador['activo'] !== 1) {
            flash_set('error', 'Este contador esta desactivado, no se le pueden registrar lecturas nuevas.');
            return redirect()->to('/lecturas');
        }

        $ultima = $this->lecturas->ultimaDeContador((int) $contadorId);

        $data['titulo']        = 'Registrar lectura';
        $data['contador']      = $contador;
        $data['cliente']       = model('ClienteModel')->find($contador['cliente_id']);
        $data['tarifaBase']    = $this->tarifas->vigentePara((int) $contador['tipo_servicio_id']);
        $data['lectura_anterior'] = $ultima['lectura_actual'] ?? 0;
        $data['ultima_fecha']  = $ultima['fecha'] ?? null;
        // CAMBIO: fecha automatica del sistema (ya no se pide al usuario)
        $data['fecha']         = date('Y-m-d H:i:s');

        return view('Lecturas/nueva', $data);
    }

    /**
     * Guarda una lectura. CAMBIOS:
     * - La fecha es la actual del sistema (no se recibe del formulario).
     * - Regla de UNA lectura por contador por mes de calendario.
     * - Calcula consumo, excedente y montos (base + exceso) automaticamente.
     */
    public function guardar()
    {
        $contadorId   = (int) $this->request->getPost('contador_id');
        $lecturaActual = (int) $this->request->getPost('lectura_actual');
        // CAMBIO: fecha automatica (antes se tomaba del POST 'fecha')
        $fecha        = date('Y-m-d H:i:s');

        $contador = $this->contadores
            ->select('Tb_Contadores.*, Tb_Tipos_Servicios.volumen_incluido_litros')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->find($contadorId);

        if (! $contador) {
            flash_set('error', 'Contador no encontrado.');
            return redirect()->to('/lecturas');
        }

        // Regla: una lectura por contador por mes de calendario
        if ($this->lecturas->existeEnMes($contadorId, $fecha)) {
            flash_set('error', 'Este contador ya tiene una lectura registrada en el mes de ' . date('F Y', strtotime($fecha)) . '. Recien el proximo mes se puede registrar una nueva lectura.');
            return redirect()->to('/lecturas');
        }

        $ultima = $this->lecturas->ultimaDeContador($contadorId);
        $anterior = $ultima['lectura_actual'] ?? 0;

        // Regla: no se permite actual < anterior
        if ($lecturaActual < $anterior) {
            flash_set('error', 'La lectura actual no puede ser menor que la anterior (' . $anterior . ').');
            return redirect()->back()->withInput();
        }

        $consumo = $lecturaActual - $anterior;

        // Tarifa base vigente
        $tarifaBase = $this->tarifas->vigentePara((int) $contador['tipo_servicio_id'], $fecha);
        if (! $tarifaBase) {
            flash_set('error', 'No hay tarifa base vigente para este tipo de servicio.');
            return redirect()->back()->withInput();
        }

        $montoBase = $tarifaBase['precio'] * $consumo;
        $montoExceso = 0;
        $tarifaExcesoId = null;

        // Excedente
        $incluido = (int) $contador['volumen_incluido_litros'];
        if ($consumo > $incluido) {
            $excedente = $consumo - $incluido;
            $tipoExceso = model('TipoServicioModel')->where('codigo', 'EXCESO')->first();
            if ($tipoExceso) {
                $tarifaExceso = $this->tarifas->vigentePara((int) $tipoExceso['id'], $fecha);
                if ($tarifaExceso) {
                    $montoExceso   = $tarifaExceso['precio'] * $excedente;
                    $tarifaExcesoId = (int) $tarifaExceso['id'];
                }
            }
        }

        // Generar numero de recibo automatico: R-AAAA-NNNN, consecutivo por anio
        $anio = (int) date('Y', strtotime($fecha));
        $conteo = $this->lecturas->contarDelAnio($anio) + 1;
        $numeroRecibo = 'R-' . $anio . '-' . str_pad((string) $conteo, 4, '0', STR_PAD_LEFT);

        $guardado = $this->lecturas->save([
            'numero_recibo'      => $numeroRecibo,
            'lectura_anterior'   => $anterior,
            'lectura_actual'     => $lecturaActual,
            'consumo_litros'     => $consumo,
            'fecha'              => $fecha,
            'contador_id'        => $contadorId,
            'tarifa_base_id'     => (int) $tarifaBase['id'],
            'tarifa_exceso_id'   => $tarifaExcesoId,
            'usuario_lector_id'  => (int) ($_SESSION['id_usuario'] ?? 0),
            'monto_base'         => $montoBase,
            'monto_exceso'       => $montoExceso,
        ]);

        if (! $guardado) {
            flash_set('error', 'No se pudo registrar la lectura. Intenta de nuevo.');
            return redirect()->back()->withInput();
        }

        flash_set('message', 'Lectura registrada. Consumo: ' . $consumo . ' L. Total: Q' . number_format($montoBase + $montoExceso, 2));
        return redirect()->to('/lecturas');
    }

    /**
     * Formulario de edicion de la lectura vigente del contador.
     * NUEVO METODO. Solo se permite editar la lectura mas reciente del mes
     * (esUltimaDeContador) y unicamente si aun no tiene pago registrado.
     */
    public function editar($lecturaId = null)
    {
        $lectura = $this->lecturas->find($lecturaId);

        if (! $lectura) {
            flash_set('error', 'Lectura no encontrada.');
            return redirect()->to('/lecturas');
        }

        $contadorId = (int) $lectura['contador_id'];

        // Solo se puede editar la lectura vigente del mes y sin pago
        if (! $this->lecturas->esUltimaDeContador((int) $lectura['id'], $contadorId)) {
            flash_set('error', 'Esta lectura ya no se puede editar porque corresponde a un mes anterior.');
            return redirect()->to('/lecturas');
        }

        if ($this->lecturas->tienePago((int) $lectura['id'])) {
            flash_set('error', 'Esta lectura ya fue pagada y no se puede editar.');
            return redirect()->to('/lecturas');
        }

        $contador = $this->contadores
            ->select('Tb_Contadores.*, Tb_Clientes.nombre as cliente_nombre, Tb_Sectores.nombre as sector_nombre, Tb_Tipos_Servicios.nombre as tipo_nombre, Tb_Tipos_Servicios.volumen_incluido_litros')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->join('Tb_Sectores', 'Tb_Sectores.id = Tb_Contadores.sector_id')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->find($contadorId);

        $data['titulo']        = 'Editar lectura';
        $data['lectura']       = $lectura;
        $data['contador']      = $contador;
        $data['cliente']       = model('ClienteModel')->find($contador['cliente_id']);
        $data['tarifaBase']    = $this->tarifas->vigentePara((int) $contador['tipo_servicio_id'], $lectura['fecha']);

        return view('Lecturas/editar', $data);
    }

    /**
     * Guarda los cambios de la lectura vigente y recalcula consumo y montos.
     * NUEVO METODO. Mantiene la fecha y lectura anterior originales; solo
     * cambia la lectura actual y el monto derivado.
     */
    public function actualizar()
    {
        $lecturaId    = (int) $this->request->getPost('lectura_id');
        $lecturaActual = (int) $this->request->getPost('lectura_actual');

        $lectura = $this->lecturas->find($lecturaId);

        if (! $lectura) {
            flash_set('error', 'Lectura no encontrada.');
            return redirect()->to('/lecturas');
        }

        $contadorId = (int) $lectura['contador_id'];

        if (! $this->lecturas->esUltimaDeContador((int) $lectura['id'], $contadorId)) {
            flash_set('error', 'Esta lectura ya no se puede editar porque corresponde a un mes anterior.');
            return redirect()->to('/lecturas');
        }

        if ($this->lecturas->tienePago((int) $lectura['id'])) {
            flash_set('error', 'Esta lectura ya fue pagada y no se puede editar.');
            return redirect()->to('/lecturas');
        }

        $anterior = (int) $lectura['lectura_anterior'];
        $fecha    = $lectura['fecha'];

        if ($lecturaActual < $anterior) {
            flash_set('error', 'La lectura actual no puede ser menor que la anterior (' . $anterior . ').');
            return redirect()->back()->withInput();
        }

        $contador = $this->contadores
            ->select('Tb_Contadores.*, Tb_Tipos_Servicios.volumen_incluido_litros')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->find($contadorId);

        $consumo = $lecturaActual - $anterior;

        $tarifaBase = $this->tarifas->vigentePara((int) $contador['tipo_servicio_id'], $fecha);
        if (! $tarifaBase) {
            flash_set('error', 'No hay tarifa base vigente para este tipo de servicio.');
            return redirect()->back()->withInput();
        }

        $montoBase = $tarifaBase['precio'] * $consumo;
        $montoExceso = 0;
        $tarifaExcesoId = null;

        $incluido = (int) $contador['volumen_incluido_litros'];
        if ($consumo > $incluido) {
            $excedente = $consumo - $incluido;
            $tipoExceso = model('TipoServicioModel')->where('codigo', 'EXCESO')->first();
            if ($tipoExceso) {
                $tarifaExceso = $this->tarifas->vigentePara((int) $tipoExceso['id'], $fecha);
                if ($tarifaExceso) {
                    $montoExceso   = $tarifaExceso['precio'] * $excedente;
                    $tarifaExcesoId = (int) $tarifaExceso['id'];
                }
            }
        }
        
        $actualizado = $this->lecturas->update($lecturaId, [
            'lectura_actual'   => $lecturaActual,
            'consumo_litros'   => $consumo,
            'tarifa_base_id'   => (int) $tarifaBase['id'],
            'tarifa_exceso_id' => $tarifaExcesoId,
            'monto_base'       => $montoBase,
            'monto_exceso'     => $montoExceso,
        ]);

        if (! $actualizado) {
            flash_set('error', 'No se pudo actualizar la lectura. Intenta de nuevo.');
            return redirect()->back()->withInput();
        }

        flash_set('message', 'Lectura actualizada. Consumo: ' . $consumo . ' L. Total: Q' . number_format($montoBase + $montoExceso, 2));
        return redirect()->to('/lecturas');
    }
}