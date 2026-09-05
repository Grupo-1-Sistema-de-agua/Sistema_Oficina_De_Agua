<?php

namespace App\Controllers\Recibos;

use App\Controllers\BaseController;
use App\Models\ReciboModel;
use App\Models\ClienteModel; // Lo necesitamos para el formulario

class RecibosController extends BaseController
{
    protected $reciboModel;
    protected $clienteModel;

    public function __construct()
    {
        $this->reciboModel = new ReciboModel();
        $this->clienteModel = new ClienteModel();
    }

    // READ: Cargar la vista con los datos
    public function index()
    {
        // Obtenemos todos los recibos
        $data['recibos'] = $this->reciboModel->findAll();
        // Obtenemos clientes para llenar el <select> en el modal de nuevo recibo
        $data['clientes'] = $this->clienteModel->findAll();
        
        return view('Recibos/index', $data);
    }

    // CREATE: Guardar un nuevo recibo
    public function store()
    {
        $datos = [
            'numero_recibo'   => $this->request->getPost('numero_recibo'),
            'id_cliente'      => $this->request->getPost('id_cliente'),
            'nombre_cliente'  => $this->request->getPost('nombre_cliente'),
            'direccion'       => $this->request->getPost('direccion'),
            'numero_contador' => $this->request->getPost('numero_contador'),
            'monto_total'     => $this->request->getPost('monto_total'),
            'fecha_emision'   => $this->request->getPost('fecha_emision')
        ];

        // Validamos y guardamos usando las reglas del modelo
        if (!$this->reciboModel->save($datos)) {
            return redirect()->back()->withInput()->with('errores', $this->reciboModel->errors());
        }

        return redirect()->to('/recibos')->with('mensaje', 'Recibo generado exitosamente.');
    }

    // UPDATE: Editar un recibo
    public function update($id = null)
    {
        $datos = [
            'numero_recibo'   => $this->request->getPost('numero_recibo'),
            'id_cliente'      => $this->request->getPost('id_cliente'),
            'nombre_cliente'  => $this->request->getPost('nombre_cliente'),
            'direccion'       => $this->request->getPost('direccion'),
            'numero_contador' => $this->request->getPost('numero_contador'),
            'monto_total'     => $this->request->getPost('monto_total'),
            'fecha_emision'   => $this->request->getPost('fecha_emision')
        ];

        if (!$this->reciboModel->update($id, $datos)) {
            return redirect()->back()->withInput()->with('errores', $this->reciboModel->errors());
        }

        return redirect()->to('/recibos')->with('mensaje', 'Recibo actualizado exitosamente.');
    }

    // DELETE: Anular recibo (Soft Delete automático gracias al Modelo)
    public function delete($id = null)
    {
        if ($id) {
            $this->reciboModel->delete($id);
            return redirect()->to('/recibos')->with('mensaje', 'Recibo anulado exitosamente.');
        }
        return redirect()->to('/recibos');
    }
}