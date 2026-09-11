<?php

namespace App\Controllers\Pagos;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use App\Models\LecturaModel;
use App\Models\MetodoPagoModel;
use App\Models\PagoModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PagosController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereRol(['secretaria', 'administrador']);
    }

    public function index()
    {
        $pagos = (new PagoModel())
            ->select('Tb_Pagos.*, Tb_Lecturas.numero_recibo, Tb_Clientes.nombre AS cliente_nombre, Tb_Metodos_Pago.nombre AS metodo_nombre, Tb_Usuarios.nombre AS usuario_nombre')
            ->join('Tb_Lecturas', 'Tb_Lecturas.id = Tb_Pagos.lectura_id')
            ->join('Tb_Contadores', 'Tb_Contadores.id = Tb_Lecturas.contador_id')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->join('Tb_Metodos_Pago', 'Tb_Metodos_Pago.id = Tb_Pagos.metodo_id')
            ->join('Tb_Usuarios', 'Tb_Usuarios.id = Tb_Pagos.usuario_registro_id')
            ->orderBy('Tb_Pagos.fecha_pago', 'DESC')
            ->findAll();

        // "Pendiente" = no tiene ningun pago ACTIVO (lectura_id_activa).
        // Si su unico pago fue anulado, vuelve a aparecer aqui.
        $lecturasPendientes = (new LecturaModel())
            ->select('Tb_Lecturas.id, Tb_Lecturas.numero_recibo, Tb_Lecturas.fecha, Tb_Lecturas.consumo_litros, Tb_Lecturas.monto_base, Tb_Lecturas.monto_exceso, Tb_Clientes.nombre AS cliente_nombre, Tb_Clientes.telefono, Tb_Clientes.direccion_principal')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id_activa = Tb_Lecturas.id', 'left')
            ->join('Tb_Contadores', 'Tb_Contadores.id = Tb_Lecturas.contador_id')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->where('Tb_Pagos.id', null)
            ->orderBy('Tb_Lecturas.fecha', 'ASC')
            ->findAll();

        $estadosCuenta = (new ClienteModel())
            ->select('Tb_Clientes.id, Tb_Clientes.nombre, Tb_Clientes.telefono, Tb_Clientes.direccion_principal, COUNT(DISTINCT CASE WHEN Tb_Lecturas.id IS NOT NULL AND Tb_Pagos.id IS NULL THEN Tb_Lecturas.id END) AS lecturas_pendientes', false)
            ->join('Tb_Contadores', 'Tb_Contadores.cliente_id = Tb_Clientes.id', 'left')
            ->join('Tb_Lecturas', 'Tb_Lecturas.contador_id = Tb_Contadores.id', 'left')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id_activa = Tb_Lecturas.id', 'left')
            ->groupBy('Tb_Clientes.id')
            ->orderBy('Tb_Clientes.nombre', 'ASC')
            ->findAll();

        return view('Pagos/index', [
            'pagos'              => $pagos,
            'lecturasPendientes' => $lecturasPendientes,
            'estadosCuenta'      => $estadosCuenta,
        ]);
    }

    /**
     * Formulario para pagar UNA lectura especifica. El monto viene fijo,
     * calculado desde la lectura (no es editable). Genera un token de
     * idempotencia propio de esta lectura, para que un doble envio del
     * formulario no genere dos pagos.
     */
    public function nuevo(int $lecturaId)
    {
        $lectura = (new LecturaModel())
            ->select('Tb_Lecturas.*, Tb_Clientes.nombre AS cliente_nombre, Tb_Contadores.codigo_fisico')
            ->join('Tb_Contadores', 'Tb_Contadores.id = Tb_Lecturas.contador_id')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->find($lecturaId);

        if (! $lectura) {
            flash_set('error', 'Lectura no encontrada.');
            return redirect()->to('/pagos');
        }

        $yaTienePagoActivo = (new PagoModel())->where('lectura_id_activa', $lecturaId)->first();
        if ($yaTienePagoActivo) {
            flash_set('error', 'Esta lectura ya tiene un pago registrado.');
            return redirect()->to('/pagos');
        }

        $token = bin2hex(random_bytes(16));
        $_SESSION['pago_token'][$lecturaId] = $token;

        return view('Pagos/nuevo', [
            'lectura' => $lectura,
            'metodos' => (new MetodoPagoModel())->orderBy('nombre', 'ASC')->findAll(),
            'token'   => $token,
        ]);
    }

    public function store()
    {
        if (! $this->request->is('post')) {
            return redirect()->to('/pagos');
        }

        $lecturaId    = (int) $this->request->getPost('lectura_id');
        $tokenEnviado = (string) $this->request->getPost('pago_token');
        $metodoId     = (int) $this->request->getPost('metodo_id');

        $tokenEsperado = $_SESSION['pago_token'][$lecturaId] ?? null;

        if (! $tokenEsperado || ! hash_equals($tokenEsperado, $tokenEnviado)) {
            // No coincide: o es un reenvio de una peticion que ya se
            // proceso (doble clic), o es invalida. Si el pago ya existe,
            // no es un error real, solo confirmamos que ya esta hecho.
            $yaExiste = (new PagoModel())->where('lectura_id_activa', $lecturaId)->first();
            if ($yaExiste) {
                flash_set('message', 'Este pago ya habia sido registrado.');
                return redirect()->to('/pagos');
            }

            flash_set('error', 'Tu sesion expiro o la solicitud no es valida. Intenta de nuevo.');
            return redirect()->to('/pagos');
        }

        // Se consume el token de inmediato: un segundo envio, aunque
        // llegue con el mismo token (doble clic muy rapido), ya no lo
        // encuentra valido.
        unset($_SESSION['pago_token'][$lecturaId]);

        $lectura = (new LecturaModel())->find($lecturaId);
        if (! $lectura) {
            flash_set('error', 'Lectura no encontrada.');
            return redirect()->to('/pagos');
        }

        if (! (new MetodoPagoModel())->find($metodoId)) {
            flash_set('error', 'El metodo de pago seleccionado no existe.');
            return redirect()->to('/pagos');
        }

        $yaTienePagoActivo = (new PagoModel())->where('lectura_id_activa', $lecturaId)->first();
        if ($yaTienePagoActivo) {
            flash_set('message', 'Este pago ya habia sido registrado.');
            return redirect()->to('/pagos');
        }

        $modelo = new PagoModel();

        try {
            $insertado = $modelo->insert([
                // El monto viene de la lectura, nunca del formulario.
                'monto'               => $lectura['monto_base'] + $lectura['monto_exceso'],
                'fecha_pago'          => date('Y-m-d H:i:s'),
                'lectura_id'          => $lecturaId,
                'metodo_id'           => $metodoId,
                'usuario_registro_id' => (int) ($_SESSION['id_usuario'] ?? 0),
                'anulado'             => 0,
            ]);
        } catch (DatabaseException $e) {
            // Choco con la columna calculada: perdio una carrera muy
            // cerrada contra otra peticion casi simultanea.
            $insertado = false;
        }

        if (! $insertado) {
            flash_set('error', 'No se pudo registrar el pago (es posible que ya se haya registrado desde otro lugar).');
            return redirect()->to('/pagos');
        }

        flash_set('message', 'Pago registrado correctamente.');
        return redirect()->to('/pagos');
    }

    public function anular(int $id)
    {
        $modelo = new PagoModel();
        $pago = $modelo->find($id);

        if (! $pago) {
            flash_set('error', 'Pago no encontrado.');
            return redirect()->to('/pagos');
        }

        if ((int) $pago['anulado'] === 1) {
            flash_set('error', 'Este pago ya estaba anulado.');
            return redirect()->to('/pagos');
        }

        $modelo->update($id, ['anulado' => 1]);

        flash_set('message', 'Pago anulado correctamente.');
        return redirect()->to('/pagos');
    }
}