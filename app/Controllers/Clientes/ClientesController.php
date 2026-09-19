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
    public function exportar()
    {
        $clientes = db_connect()->table('Tb_Clientes')
            ->select('Tb_Clientes.nombre, Tb_Clientes.dpi, Tb_Clientes.telefono, Tb_Clientes.direccion_principal, Tb_Contadores.codigo_fisico, Tb_Contadores.direccion_servicio, Tb_Contadores.activo')
            ->join('Tb_Contadores', 'Tb_Contadores.cliente_id = Tb_Clientes.id', 'left')
            ->orderBy('Tb_Clientes.nombre', 'ASC')
            ->orderBy('Tb_Contadores.codigo_fisico', 'ASC')
            ->get()->getResultArray();
        $archivo = fopen('php://temp', 'r+');

        fputcsv($archivo, [
            'Nombre',
            'DPI',
            'Telefono',
            'Direccion',
            'Contador',
            'Direccion del servicio',
            'Estado del contador',
        ], ';');

        foreach ($clientes as $cliente) {
            fputcsv($archivo, [
                $cliente['nombre'],
                $cliente['dpi'] ?? '',
                $cliente['telefono'] ?? '',
                $cliente['direccion_principal'],
                $cliente['codigo_fisico'] ?? '',
                $cliente['direccion_servicio'] ?? '',
                isset($cliente['activo'])
                    ? ((int) $cliente['activo'] === 1 ? 'Activo' : 'Inactivo')
                    : '',
            ], ';');
        }

        rewind($archivo);
        $contenido = "\xEF\xBB\xBF" . stream_get_contents($archivo);
        fclose($archivo);

        return $this->response->download('clientes.csv', $contenido);
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