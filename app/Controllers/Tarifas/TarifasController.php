<?php

namespace App\Controllers\Tarifas;

use App\Controllers\BaseController;
use App\Models\TarifaModel;
use App\Models\TipoServicioModel;

class TarifasController extends BaseController
{
    public function index()
    {
        return view('Tarifas/index');
    }

    public function create()
    {
        $tipoModel = new TipoServicioModel();

        return view('Tarifas/create', [
            'titulo' => 'Nueva Tarifa',
            'tipos'  => $tipoModel->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $tarifaModel = new TarifaModel();
        $tipoModel   = new TipoServicioModel();

        $tipoServicioId = (int) $this->request->getPost('tipo_servicio_id');
        $precio         = $this->request->getPost('precio');
        $fechaInput     = trim((string) $this->request->getPost('vigente_desde'));

        $fecha = \DateTime::createFromFormat('Y-m-d', $fechaInput);

        if (! $fecha) {
            return view('Tarifas/create', [
                'titulo' => 'Nueva Tarifa',
                'tipos'  => $tipoModel->orderBy('nombre', 'ASC')->findAll(),
                'errors' => ['vigente_desde' => 'La fecha de vigencia no es valida.'],
                'old'    => $this->request->getPost(),
            ]);
        }

        // Normaliza para poder comparar solo la parte de la fecha.
        $fecha->setTime(0, 0, 0);
        $hoy = new \DateTime('today');
        $esHoy = $fecha->format('Y-m-d') === $hoy->format('Y-m-d');

        // Si se eligió el díaa de hoy, la tarifa aplica de inmediato con la hora actual.
        // Caso contrario, si es una fecha futura, aplica desde el inicio de ese dia a las 00:00:00.
        $vigenteDesde = $esHoy
            ? date('Y-m-d H:i:s')
            : $fecha->format('Y-m-d') . ' 00:00:00';

        // No se permite otra tarifa del mismo tipo con la misma fecha de vigencia
        $duplicada = $tarifaModel
            ->where('tipo_servicio_id', $tipoServicioId)
            ->where('vigente_desde', $vigenteDesde)
            ->first();

        if ($duplicada) {
            return view('Tarifas/create', [
                'titulo' => 'Nueva Tarifa',
                'tipos'  => $tipoModel->orderBy('nombre', 'ASC')->findAll(),
                'errors' => ['vigente_desde' => 'Ya existe una tarifa de este tipo con esa misma fecha de vigencia.'],
                'old'    => $this->request->getPost(),
            ]);
        }

        $data = [
            'tipo_servicio_id' => $tipoServicioId,
            'precio'           => $precio,
            'vigente_desde'    => $vigenteDesde,
        ];

        if (! $tarifaModel->validate($data)) {
            return view('Tarifas/create', [
                'titulo' => 'Nueva Tarifa',
                'tipos'  => $tipoModel->orderBy('nombre', 'ASC')->findAll(),
                'errors' => $tarifaModel->errors(),
                'old'    => $this->request->getPost(),
            ]);
        }

        $tarifaModel->insert($data);

        return redirect()->to(base_url('tarifas'))
            ->with('message', 'Tarifa registrada correctamente.');
    }
}