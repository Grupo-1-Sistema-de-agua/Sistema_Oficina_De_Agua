<?php

namespace App\Controllers\Recibos;

use App\Controllers\BaseController;
use App\Models\ReciboModel;
use App\Models\ClienteModel;
use App\Models\ContadorModel; // Importante

class RecibosController extends BaseController
{
    protected $reciboModel;
    protected $clienteModel;
    protected $contadorModel; // <--- ¡Esta era la línea que faltaba declarar!

    public function __construct()
    {
        $this->reciboModel = new ReciboModel();
        $this->clienteModel = new ClienteModel();
        $this->contadorModel = new ContadorModel();
    }

    public function index()
    {
        $clienteModel  = new \App\Models\ClienteModel(); // Asumiendo que ya lo tenías
        $contadorModel = new \App\Models\ContadorModel();
        $pagoModel     = new \App\Models\PagoModel();

        // Traer todos los recibos para la tabla principal
        $data['recibos'] = $this->reciboModel->findAll();

        // Traer datos para llenar los <select> del modal de creación
        $data['clientes']   = $clienteModel->findAll(); 
        $data['contadores'] = $contadorModel->where('activo', 1)->findAll(); // Solo contadores activos
        $data['pagos']      = $pagoModel->findAll();

        return view('recibos/index', $data);
    }

    public function store()
    {
        // Validación de los datos del formulario
        $validationRules = [
            'nombre_cliente'  => 'required|max_length[150]',
            'direccion'       => 'required|max_length[255]',
            'numero_contador' => 'permit_empty|max_length[50]',
            'monto_total'     => 'required|numeric'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
        }
        // Generar un número de recibo automático (Ej: REC-A1B2C)
        $numeroRecibo = 'REC-' . strtoupper(substr(uniqid(), -5));

        // Capturar los datos del formulario (incluyendo los temporales)
        $data = [
            'numero_recibo'   => $numeroRecibo,
            'nombre_cliente'  => $this->request->getPost('nombre_cliente'),
            'direccion'       => $this->request->getPost('direccion'),
            'numero_contador' => $this->request->getPost('numero_contador'),
            'monto_total'     => $this->request->getPost('monto_total'),
            'fecha_emision'   => date('Y-m-d')
        ];

        // Insertar en la base de datos
        $this->reciboModel->insert($data);

    return redirect()->to('/recibos')->with('message', 'Recibo generado con éxito (Modo Prueba).');
    }

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

    public function anular($id = null)
    {
        if ($this->reciboModel->delete($id)) {
            return redirect()->to('/recibos')->with('message', 'El recibo ha sido anulado correctamente.');
        }

        return redirect()->to('/recibos')->with('error', 'No se pudo anular el recibo.');
    }
    
    public function imprimir($id = null)
    {
        $recibo = $this->reciboModel->find($id);

        if (!$recibo) {
            return redirect()->to('/recibos')->with('errores', ['El recibo solicitado no existe.']);
        }

        $data['recibo'] = $recibo;
        return view('recibos/ticket', $data);
    }
}