<?php

namespace App\Controllers\Clientes;

use App\Controllers\BaseController;
use App\Models\ClienteModel; // Importamos el modelo que ya creaste
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Modulo: Clientes
 *
 * Punto de partida para este modulo. Reemplacen index() y agreguen
 * los metodos que necesiten (create, store, edit, update, delete, etc.).
 * Las vistas de este modulo van en app/Views/Clientes/.
 * El Model correspondiente ya existe en app/Models/.
 */
class ClientesController extends BaseController
{
    protected $clienteModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereRol(['secretaria', 'administrador']);
    }

    public function __construct()
    {
        // Instanciamos el modelo para usarlo en todos los métodos
        $this->clienteModel = new ClienteModel();
    }

    // READ: Cargar la vista con los datos
    public function index()
    {
        // Obtenemos todos los registros de Tb_Clientes
        $data['clientes'] = $this->clienteModel->findAll();
        
        // Enviamos la variable $data a la vista
        return view('Clientes/index', $data);
    }

    // CREATE: Guardar un nuevo cliente
    public function store()
    {
        $datos = [
            'nombre'              => $this->request->getPost('nombre'),
            'telefono'            => $this->request->getPost('telefono'),
            'direccion_principal' => $this->request->getPost('direccion_principal')
        ];

        // Intentamos guardar. Si falla por las reglas de validación del Modelo, regresamos los errores.
        if (!$this->clienteModel->save($datos)) {
            flash_set('error', $this->clienteModel->errors());
            return redirect()->back()->withInput();
        }

        flash_set('message', 'Cliente creado exitosamente.');
        return redirect()->to('/clientes');
    }

    // UPDATE: Actualizar un cliente existente
    public function update($id = null)
    {
        $datos = [
            'nombre'              => $this->request->getPost('nombre'),
            'telefono'            => $this->request->getPost('telefono'),
            'direccion_principal' => $this->request->getPost('direccion_principal')
        ];

        // Intentamos actualizar. Aplica las mismas validaciones de tu modelo.
        if (!$this->clienteModel->update($id, $datos)) {
            flash_set('error', $this->clienteModel->errors());
            return redirect()->back()->withInput();
        }

        flash_set('message', 'Cliente actualizado exitosamente.');
        return redirect()->to('/clientes');
    }

    // DELETE: Eliminar un cliente
    public function delete($id = null)
    {
        if ($id) {
            $this->clienteModel->delete($id);
            flash_set('message', 'Cliente eliminado exitosamente.');
            return redirect()->to('/clientes');
        }
        return redirect()->to('/clientes');
    }
}