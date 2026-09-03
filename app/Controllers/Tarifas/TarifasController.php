<?php

namespace App\Controllers\Tarifas;

use App\Controllers\BaseController;
use App\Models\TarifaModel;
use App\Models\TipoServicioModel;

class TarifasController extends BaseController
{
    public function index()
    {
        $tarifaModel = new TarifaModel();
        $tipoModel   = new TipoServicioModel();

        $tipoServicioId = $this->request->getGet('tipo_servicio_id');

        $builder = $tarifaModel
            ->select('Tb_Tarifas.*, Tb_Tipos_Servicios.nombre AS tipo_nombre, Tb_Tipos_Servicios.codigo AS tipo_codigo')
            ->join('Tb_Tipos_Servicios', 'Tb_Tipos_Servicios.id = Tb_Tarifas.tipo_servicio_id')
            ->orderBy('Tb_Tarifas.vigente_desde', 'DESC');

        if (! empty($tipoServicioId)) {
            $builder->where('Tb_Tarifas.tipo_servicio_id', $tipoServicioId);
        }

        $tarifas = $builder->findAll();

        // Utiliza la lógica de vigentePara() para determinar la tarifa vigente para cada tipo de servicio
        $vigentesPorTipo = [];
        foreach ($tarifas as $tarifa) {
            $tipoId = $tarifa['tipo_servicio_id'];
            if (! array_key_exists($tipoId, $vigentesPorTipo)) {
                $vigente = $tarifaModel->vigentePara((int) $tipoId);
                $vigentesPorTipo[$tipoId] = $vigente['id'] ?? null;
            }
        }

        $ahora = date('Y-m-d H:i:s');
        foreach ($tarifas as &$tarifa) {
            if ((int) $tarifa['id'] === (int) ($vigentesPorTipo[$tarifa['tipo_servicio_id']] ?? 0)) {
                $tarifa['estado'] = 'vigente';
            } elseif ($tarifa['vigente_desde'] > $ahora) {
                $tarifa['estado'] = 'programada';
            } else {
                $tarifa['estado'] = 'historica';
            }
        }
        unset($tarifa);

        return view('Tarifas/index', [
            'titulo'           => 'Tarifas',
            'tarifas'          => $tarifas,
            'tipos'            => $tipoModel->orderBy('nombre', 'ASC')->findAll(),
            'tipoSeleccionado' => $tipoServicioId,
        ]);
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