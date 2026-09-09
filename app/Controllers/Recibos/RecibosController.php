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
        $data['recibos'] = $this->reciboModel->findAll();
        $data['clientes'] = $this->clienteModel->findAll();
        $data['contadores'] = $this->contadorModel->findAll();
        
        // Generación Automática del Número de Recibo
        $ultimoRecibo = $this->reciboModel->orderBy('id', 'DESC')->first();
        if ($ultimoRecibo && isset($ultimoRecibo['numero_recibo'])) {
            $partes = explode('-', $ultimoRecibo['numero_recibo']);
            $siguienteNumero = isset($partes[1]) ? intval($partes[1]) + 1 : intval($ultimoRecibo['numero_recibo']) + 1;
            $data['siguiente_recibo'] = 'REC-' . str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);
        } else {
            $data['siguiente_recibo'] = 'REC-001';
        }
        
        return view('recibos/index', $data);
    }

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

        if (!$this->reciboModel->save($datos)) {
            return redirect()->back()->withInput()->with('errores', $this->reciboModel->errors());
        }

        return redirect()->to('/recibos')->with('mensaje', 'Recibo generado exitosamente.');
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