<?php

namespace App\Controllers\Clientes;

use App\Controllers\BaseController;
use App\Models\ClienteModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

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
        $this->clienteModel = new ClienteModel();
    }

    public function index()
    {
        $clientes = $this->clienteModel->findAll();

        return view('Clientes/index', ['clientes' => $clientes]);
    }

    public function nuevo()
    {
        return view('Clientes/nuevo');
    }

    public function store()
    {
        $datos = [
            'nombre'              => $this->request->getPost('nombre'),
            'telefono'            => $this->request->getPost('telefono'),
            'direccion_principal' => $this->request->getPost('direccion_principal'),
            'dpi'                 => $this->request->getPost('dpi') ?: null,
        ];

        if (! $this->clienteModel->save($datos)) {
            flash_set('error', implode(' ', $this->clienteModel->errors()));
            return redirect()->back()->withInput();
        }

        flash_set('message', 'Cliente creado exitosamente.');
        return redirect()->to('/clientes');
    }

    public function editar($id = null)
    {
        $cliente = $this->clienteModel->find($id);

        if (! $cliente) {
            flash_set('error', 'Cliente no encontrado.');
            return redirect()->to('/clientes');
        }

        return view('Clientes/editar', ['cliente' => $cliente]);
    }

    public function update($id = null)
    {
        $cliente = $this->clienteModel->find($id);

        if (! $cliente) {
            flash_set('error', 'Cliente no encontrado.');
            return redirect()->to('/clientes');
        }

        $datos = [
            'nombre'              => $this->request->getPost('nombre'),
            'telefono'            => $this->request->getPost('telefono'),
            'direccion_principal' => $this->request->getPost('direccion_principal'),
            'dpi'                 => $this->request->getPost('dpi') ?: null,
        ];

        if (! $this->clienteModel->update($id, $datos)) {
            flash_set('error', implode(' ', $this->clienteModel->errors()));
            return redirect()->back()->withInput();
        }

        flash_set('message', 'Cliente actualizado exitosamente.');
        return redirect()->to('/clientes');
    }

    public function delete($id = null)
    {
        $cliente = $this->clienteModel->find($id);

        if (! $cliente) {
            flash_set('error', 'Cliente no encontrado.');
            return redirect()->to('/clientes');
        }

        try {
            $this->clienteModel->delete($id);
            flash_set('message', 'Cliente eliminado exitosamente.');
            return redirect()->to('/clientes');
        } catch (\Throwable $e) {
            flash_set('error', 'No se puede eliminar este cliente porque tiene contadores u otros registros relacionados.');
            return redirect()->to('/clientes');
        }
    }
}