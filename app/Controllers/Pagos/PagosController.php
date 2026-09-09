<?php

namespace App\Controllers\Pagos;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use App\Models\LecturaModel;
use App\Models\MetodoPagoModel;
use App\Models\PagoModel;

class PagosController extends BaseController
{
    public function index()
    {
        return $this->renderIndex();
    }

    public function create()
    {
        return $this->renderIndex();
    }

    public function store()
    {
        if (! $this->request->is('post')) {
            return redirect()->to('/pagos');
        }

        $datos = $this->datosDelFormulario();
        $datos['fecha_pago'] = date('Y-m-d H:i:s');
        $error = $this->validarDatos($datos);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $datos['usuario_registro_id'] = (int) session()->get('usuario_id');
        $modelo = new PagoModel();

        if (! $modelo->insert($datos)) {
            return redirect()->back()->withInput()->with('error', 'No se pudo registrar el pago.');
        }

        return redirect()->to('/pagos')->with('message', 'Pago registrado correctamente.');
    }

    public function edit(int $id)
    {
        $pago = (new PagoModel())->find($id);

        if (! $pago) {
            return redirect()->to('/pagos')->with('error', 'Pago no encontrado.');
        }

        return $this->renderIndex($pago);
    }

    public function update(int $id)
    {
        if (! $this->request->is('post')) {
            return redirect()->to('/pagos');
        }

        $modelo = new PagoModel();
        if (! $modelo->find($id)) {
            return redirect()->to('/pagos')->with('error', 'Pago no encontrado.');
        }

        $datos = $this->datosDelFormulario();
        $error = $this->validarDatos($datos, $id);

        if ($error !== null) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        if (! $modelo->update($id, $datos)) {
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar el pago.');
        }

        return redirect()->to('/pagos')->with('message', 'Pago actualizado correctamente.');
    }

    public function delete()
    {
        if (! $this->request->is('post')) {
            return redirect()->to('/pagos');
        }

        $id = (int) $this->request->getPost('pago_id');
        $modelo = new PagoModel();

        if (! $modelo->find($id)) {
            return redirect()->to('/pagos')->with('error', 'Pago no encontrado.');
        }

        $modelo->delete($id);

        return redirect()->to('/pagos')->with('message', 'Pago eliminado correctamente.');
    }

    private function renderIndex(?array $pago = null)
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

        $lecturas = (new LecturaModel())
            ->select('Tb_Lecturas.id, Tb_Lecturas.numero_recibo, Tb_Lecturas.fecha, Tb_Lecturas.consumo_litros, Tb_Clientes.nombre AS cliente_nombre')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id = Tb_Lecturas.id', 'left')
            ->join('Tb_Contadores', 'Tb_Contadores.id = Tb_Lecturas.contador_id')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->groupStart()
                ->where('Tb_Pagos.id', null)
                ->orWhere('Tb_Pagos.id', $pago['id'] ?? 0)
            ->groupEnd()
            ->orderBy('Tb_Lecturas.fecha', 'DESC')
            ->findAll();

        $lecturasPendientes = (new LecturaModel())
            ->select('Tb_Lecturas.id, Tb_Lecturas.numero_recibo, Tb_Lecturas.fecha, Tb_Lecturas.consumo_litros, Tb_Clientes.nombre AS cliente_nombre, Tb_Clientes.telefono, Tb_Clientes.direccion_principal')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id = Tb_Lecturas.id', 'left')
            ->join('Tb_Contadores', 'Tb_Contadores.id = Tb_Lecturas.contador_id')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->where('Tb_Pagos.id', null)
            ->orderBy('Tb_Lecturas.fecha', 'ASC')
            ->findAll();

        $estadosCuenta = (new ClienteModel())
            ->select('Tb_Clientes.id, Tb_Clientes.nombre, Tb_Clientes.telefono, Tb_Clientes.direccion_principal, COUNT(DISTINCT CASE WHEN Tb_Lecturas.id IS NOT NULL AND Tb_Pagos.id IS NULL THEN Tb_Lecturas.id END) AS lecturas_pendientes', false)
            ->join('Tb_Contadores', 'Tb_Contadores.cliente_id = Tb_Clientes.id', 'left')
            ->join('Tb_Lecturas', 'Tb_Lecturas.contador_id = Tb_Contadores.id', 'left')
            ->join('Tb_Pagos', 'Tb_Pagos.lectura_id = Tb_Lecturas.id', 'left')
            ->groupBy('Tb_Clientes.id')
            ->orderBy('Tb_Clientes.nombre', 'ASC')
            ->findAll();

        return view('Pagos/index', [
            'pagos'              => $pagos,
            'lecturas'           => $lecturas,
            'lecturasPendientes' => $lecturasPendientes,
            'metodos'            => (new MetodoPagoModel())->orderBy('nombre', 'ASC')->findAll(),
            'estadosCuenta'      => $estadosCuenta,
            'pagoEditar'         => $pago,
        ]);
    }

    private function datosDelFormulario(): array
    {
        return [
            'monto'      => trim((string) $this->request->getPost('monto')),
            'fecha_pago' => trim((string) $this->request->getPost('fecha_pago')),
            'lectura_id' => (int) $this->request->getPost('lectura_id'),
            'metodo_id'  => (int) $this->request->getPost('metodo_id'),
        ];
    }

    private function validarDatos(array $datos, ?int $pagoId = null): ?string
    {
        if (! is_numeric($datos['monto']) || (float) $datos['monto'] <= 0) {
            return 'El monto debe ser un numero mayor que cero.';
        }

        if ($datos['fecha_pago'] === '' || strtotime($datos['fecha_pago']) === false) {
            return 'La fecha del pago no es valida.';
        }

        if (! (new LecturaModel())->find($datos['lectura_id'])) {
            return 'La lectura seleccionada no existe.';
        }

        if (! (new MetodoPagoModel())->find($datos['metodo_id'])) {
            return 'El metodo de pago seleccionado no existe.';
        }

        $pagoExistente = (new PagoModel())->where('lectura_id', $datos['lectura_id'])->first();
        if ($pagoExistente && (int) $pagoExistente['id'] !== $pagoId) {
            return 'La lectura seleccionada ya tiene un pago registrado.';
        }

        return null;
    }
}
