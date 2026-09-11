<?php

namespace App\Controllers\Recibos;

use App\Controllers\BaseController;
use App\Models\ReciboModel;
use App\Models\ClienteModel;
use App\Models\ContadorModel;
use App\Models\LecturaModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class RecibosController extends BaseController
{
    protected $reciboModel;
    protected $clienteModel;
    protected $contadorModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereRol(['secretaria', 'administrador']);
    }

    public function __construct()
    {
        $this->reciboModel = new ReciboModel();
        $this->clienteModel = new ClienteModel();
        $this->contadorModel = new ContadorModel();
    }

    public function index()
    {
        $lecturaModel = new LecturaModel();

        // Traer todos los recibos para la tabla principal
        $data['recibos'] = $this->reciboModel->findAll();

        // Traer catálogos básicos
        $data['clientes']   = $this->clienteModel->findAll(); 
        $data['contadores'] = $this->contadorModel->where('activo', 1)->findAll(); 
        
        // Crear un mapa de lecturas pendientes asociadas por el ID del contador
        $lecturasPendientes = $lecturaModel->pendientesDePago();
        $mapaLecturas = [];
        foreach ($lecturasPendientes as $l) {
            $total = $l['monto_base'] + $l['monto_exceso'];
            $mapaLecturas[$l['contador_id']] = [
                'consumo' => $l['consumo_litros'],
                'monto'   => $total
            ];
        }
        $data['mapaLecturas'] = $mapaLecturas;

        return view('recibos/index', $data);
    }

    public function store()
    {
        $validationRules = [
            'nombre_cliente'  => 'required|max_length[150]',
            'direccion'       => 'required|max_length[255]',
            'numero_contador' => 'permit_empty|max_length[50]',
            'monto_total'     => 'required|numeric'
        ];

        if (!$this->validate($validationRules)) {
            flash_set('errores', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
        
        $numeroRecibo = 'REC-' . strtoupper(substr(uniqid(), -5));

        $data = [
            'numero_recibo'   => $numeroRecibo,
            'nombre_cliente'  => $this->request->getPost('nombre_cliente'),
            'direccion'       => $this->request->getPost('direccion'),
            'numero_contador' => $this->request->getPost('numero_contador'),
            'monto_total'     => $this->request->getPost('monto_total'),
            'fecha_emision'   => $this->request->getPost('fecha_emision'),
            'consumo_litros'  => $this->request->getPost('consumo_litros') // Se recibe del form oculto
        ];

        $this->reciboModel->insert($data);

        flash_set('mensaje', 'Recibo generado con éxito.');
        return redirect()->to('/recibos');
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
            flash_set('errores', $this->reciboModel->errors());
            return redirect()->back()->withInput();
        }

        flash_set('mensaje', 'Recibo actualizado exitosamente.');
        return redirect()->to('/recibos');
    }

    public function anular($id = null)
    {
        if ($this->reciboModel->delete($id)) {
            flash_set('mensaje', 'El recibo ha sido anulado correctamente.');
            return redirect()->to('/recibos');
        }

        flash_set('errores', ['No se pudo anular el recibo.']);
        return redirect()->to('/recibos');
    }
    
    public function imprimir($id = null)
    {
        $recibo = $this->reciboModel->find($id);

        if (!$recibo) {
            flash_set('errores', ['El recibo solicitado no existe.']);
            return redirect()->to('/recibos');
        }

        $data['recibo'] = $recibo;
        return view('recibos/ticket', $data);
    }
}