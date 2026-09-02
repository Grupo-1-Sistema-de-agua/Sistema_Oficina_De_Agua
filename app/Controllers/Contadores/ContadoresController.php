<?php

namespace App\Controllers\Contadores;




/**
 * Modulo: Contadores
 *
 * Punto de partida para este modulo. Reemplacen index() y agreguen
 * los metodos que necesiten (create, store, edit, update, delete, etc.).
 * Las vistas de este modulo van en app/Views/Contadores/.
 * El Model correspondiente ya existe en app/Models/.
 */




use App\Controllers\BaseController;
use App\Models\ContadorModel;
use App\Models\ClienteModel;
use App\Models\TipoServicioModel;
use App\Models\SectorModel;
class ContadoresController extends BaseController
{

  private ContadorModel $contadores;

    public function __construct()
    {
        $this->contadores = model('ContadorModel');
    }



    // Listar contadores (con datos del cliente, tipo y sector)
    public function index()
            {
        $data['titulo']   = 'Contadores';
        $data['contadores'] = $this->contadores
            ->select('Tb_Contadores.*, Tb_Clientes.nombre as cliente_nombre, Tb_Tipos_Servicios.nombre as tipo_nombre, Tb_Sectores.nombre as sector_nombre')
            ->join('Tb_Clientes', 'Tb_Clientes.id = Tb_Contadores.cliente_id')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Contadores.tipo_servicio_id')
            ->join('Tb_Sectores', 'Tb_Sectores.id = Tb_Contadores.sector_id')
            ->orderBy('Tb_Contadores.id', 'DESC')
            ->findAll();


              return view('Contadores/index', $data);

            }


    // Formulario de alta
    public function nuevo()
         {
        $data['titulo']   = 'Nuevo Contador';
        $data['clientes'] = model('ClienteModel')->findAll();
        $data['tipos']    = model('TipoServicioModel')->contratables();
        $data['sectores'] = model('SectorModel')->findAll();
        
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        return view('Contadores/form', $data);
          }


    // Guardar (POST)
    public function crear()
    {
        if (! $this->contadores->validate($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $this->contadores->errors());
        }

        $this->contadores->save($this->request->getPost());

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

    // Actualizar (POST)
    public function actualizar($id = null)
    {
        $contador = $this->contadores->find($id);
        if (! $contador) {
            return redirect()->to('/contadores')->with('error', 'Contador no encontrado.');
        }

        if (! $this->contadores->validate($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $this->contadores->errors());
        }

        $this->contadores->update($id, $this->request->getPost());

        return redirect()->to('/contadores')->with('message', 'Contador actualizado correctamente.');
    }

    // Eliminacion logica (activo = 0)
    public function eliminar($id = null)
    {
        $contador = $this->contadores->find($id);
        if (! $contador) {
            return redirect()->to('/contadores')->with('error', 'Contador no encontrado.');
        }

        $this->contadores->update($id, ['activo' => 0]);

        return redirect()->to('/contadores')->with('message', 'Contador desactivado correctamente.');
    }

}