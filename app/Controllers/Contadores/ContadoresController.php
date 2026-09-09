<?php

namespace App\Controllers\Contadores;

use App\Controllers\BaseController;
use App\Models\ContadorModel;
use App\Models\ClienteModel;
use App\Models\TipoServicioModel;
use App\Models\SectorModel;
use App\Models\LecturaModel;

class ContadoresController extends BaseController
{
    private ContadorModel $contadores;

    public function __construct()
    {
        $this->contadores = model('ContadorModel');
    }

    // Listar con filtros por texto y sector
    public function index()
    {
        $q      = $this->request->getGet('q');
        $sector = $this->request->getGet('sector');

        $data['titulo']             = 'Contadores';
        $data['contadores']         = $this->contadores->buscar($q, $sector ? (int) $sector : null);
        $data['pendientes']         = model('LecturaModel')->pendientesPorContador();
        $data['sectores']           = model('SectorModel')->findAll();
        $data['q']                  = $q;
        $data['sectorSeleccionado'] = $sector ? (int) $sector : null;

        return view('Contadores/index', $data);
    }

    // Formulario de alta
    public function nuevo()
    {
        $data['titulo']   = 'Nuevo Contador';
        $data['clientes'] = model('ClienteModel')->findAll();
        $data['tipos']    = model('TipoServicioModel')->contratables();
        $data['sectores'] = model('SectorModel')->findAll();
        $data['contador'] = null;

        $data['errors'] = session()->getFlashdata('errors') ?? [];
        return view('Contadores/form', $data);
    }

    // Guardar (POST) con regla "1 activo por cliente+direccion"
    public function crear()
    {
        if (! $this->contadores->validate($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $this->contadores->errors());
        }

        $datos = $this->request->getPost();
        if ($this->contadores->activoEnMismaDireccion($datos['cliente_id'], $datos['direccion_servicio'])) {
            return redirect()->back()->withInput()
                ->with('errors', ['direccion_servicio' => 'El cliente ya tiene un contador ACTIVO en esa direccion.']);
        }

        $datos['fecha_asignacion'] = date('Y-m-d');
        $this->contadores->save($datos);

        return redirect()->to('/contadores')->with('message', 'Contador registrado correctamente.');
    }

    // Formulario de edicion
    public function editar($id = null)
    {
        $contador = $this->contadores->find($id);
        if (! $contador) {
            return redirect()->to('/contadores')->with('error', 'Contador no encontrado.');
        }

        $data['titulo']    = 'Editar Contador';
        $data['contador']  = $contador;
        $data['clientes']  = model('ClienteModel')->findAll();
        $data['tipos']     = model('TipoServicioModel')->contratables();
        $data['sectores']  = model('SectorModel')->findAll();

        $data['errors'] = session()->getFlashdata('errors') ?? [];
        return view('Contadores/form', $data);
    }

    // Actualizar (POST) excluyendo el propio contador
    public function actualizar($id = null)
    {
        $contador = $this->contadores->find($id);
        if (! $contador) {
            return redirect()->to('/contadores')->with('error', 'Contador no encontrado.');
        }

        if (! $this->contadores->validate($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $this->contadores->errors());
        }

        $datos = $this->request->getPost();
        if ($this->contadores->activoEnMismaDireccion($datos['cliente_id'], $datos['direccion_servicio'], (int) $id)) {
            return redirect()->back()->withInput()
                ->with('errors', ['direccion_servicio' => 'El cliente ya tiene un contador ACTIVO en esa direccion.']);
        }

        $this->contadores->update($id, $datos);

        return redirect()->to('/contadores')->with('message', 'Contador actualizado correctamente.');
    }

    // Desactivar/activar logico, registrando la fecha
    public function eliminar($id = null)
    {
        $contador = $this->contadores->find($id);
        if (! $contador) {
            return redirect()->to('/contadores')->with('error', 'Contador no encontrado.');
        }

        $nuevo = ['activo' => $contador['activo'] ? 0 : 1];
        if ($nuevo['activo']) {
            if ($this->contadores->activoEnMismaDireccion($contador['cliente_id'], $contador['direccion_servicio'], (int) $id)) {
                return redirect()->to('/contadores')->with('error', 'No se puede reactivar: el cliente ya tiene un contador activo en esa direccion.');
            }
            $nuevo['fecha_desactivacion'] = null;
        } else {
            $nuevo['fecha_desactivacion'] = date('Y-m-d');
        }

        $this->contadores->update($id, $nuevo);

        return redirect()->to('/contadores')->with('message', $nuevo['activo'] ? 'Contador activado correctamente.' : 'Contador desactivado correctamente.');
    }

    // Detalle: ficha del contador + cliente + historial + otros contadores
    public function ver($id = null)
    {
        $contador = $this->contadores->find($id);
        if (! $contador) {
            return redirect()->to('/contadores')->with('error', 'Contador no encontrado.');
        }

        $data['titulo']    = 'Detalle del Contador';
        $data['contador']  = $contador;
        $data['cliente']   = model('ClienteModel')->find($contador['cliente_id']);
        $data['tipo']      = model('TipoServicioModel')->find($contador['tipo_servicio_id']);
        $data['sector']    = model('SectorModel')->find($contador['sector_id']);
        $data['historial'] = model('LecturaModel')->historialDeContador($id);
        $data['pendientes'] = model('LecturaModel')->pendientesPorContador();
        $data['otros']     = $this->contadores->deCliente($contador['cliente_id']);

        return view('Contadores/ver', $data);
    }
}