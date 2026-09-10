<?php

namespace App\Controllers\Tarifas;

use App\Controllers\BaseController;
use App\Models\TipoServicioModel;

class TiposServicioController extends BaseController
{
    public function index()
    {
        $model = new TipoServicioModel();

        return view('Tarifas/tipos_servicio/index', [
            'titulo' => 'Tipos de Servicio',
            'tipos'  => $model->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('Tarifas/tipos_servicio/create', [
            'titulo' => 'Nuevo Tipo de Servicio',
        ]);
    }

    public function store()
    {
        $model = new TipoServicioModel();

        $nombre = trim((string) $this->request->getPost('nombre'));

        $data = [
            'codigo'                  => $this->generarCodigoUnico($nombre, $model),
            'nombre'                  => $nombre,
            'volumen_incluido_litros' => $this->request->getPost('volumen_incluido_litros') ?: null,
            'es_servicio'             => $this->request->getPost('es_servicio') ?? '1',
        ];

        if (! $model->validate($data)) {
            return view('Tarifas/tipos_servicio/create', [
                'titulo' => 'Nuevo Tipo de Servicio',
                'errors' => $model->errors(),
                'old'    => $data,
            ]);
        }

        $model->insert($data);

        return redirect()->to(base_url('tipos-servicio'))
            ->with('message', 'Tipo de servicio creado correctamente.');
    }

    public function edit($id)
    {
        $model = new TipoServicioModel();
        $tipo  = $model->find($id);

        if (! $tipo) {
            return redirect()->to(base_url('tipos-servicio'))
                ->with('error', 'Tipo de servicio no encontrado.');
        }

        return view('Tarifas/tipos_servicio/edit', [
            'titulo' => 'Editar Tipo de Servicio',
            'tipo'   => $tipo,
        ]);
    }

    public function update($id)
    {
        $model = new TipoServicioModel();
        $tipo  = $model->find($id);

        if (! $tipo) {
            return redirect()->to(base_url('tipos-servicio'))
                ->with('error', 'Tipo de servicio no encontrado.');
        }

        $nombre = trim((string) $this->request->getPost('nombre'));

        $codigo = $nombre !== $tipo['nombre']
            ? $this->generarCodigoUnico($nombre, $model, $id)
            : $tipo['codigo'];

        $data = [
            'codigo'                  => $codigo,
            'nombre'                  => $nombre,
            'volumen_incluido_litros' => $this->request->getPost('volumen_incluido_litros') ?: null,
            'es_servicio'             => $this->request->getPost('es_servicio') ?? '1',
        ];

        if (! $model->validate(array_merge(['id' => $id], $data))) {
            return view('Tarifas/tipos_servicio/edit', [
                'titulo' => 'Editar Tipo de Servicio',
                'tipo'   => array_merge($tipo, $data),
                'errors' => $model->errors(),
            ]);
        }

        $model->update($id, $data);

        return redirect()->to(base_url('tipos-servicio'))
            ->with('message', 'Tipo de servicio actualizado correctamente.');
    }

    public function delete($id)
    {
        $model = new TipoServicioModel();

        $enContadores = db_connect()->table('Tb_Contadores')->where('tipo_servicio_id', $id)->countAllResults();
        $enTarifas    = db_connect()->table('Tb_Tarifas')->where('tipo_servicio_id', $id)->countAllResults();

        if ($enContadores > 0 || $enTarifas > 0) {
            return redirect()->to(base_url('tipos-servicio'))
                ->with('error', 'No se puede eliminar: este tipo de servicio tiene contadores o tarifas asociadas.');
        }

        $model->delete($id);

        return redirect()->to(base_url('tipos-servicio'))
            ->with('message', 'Tipo de servicio eliminado.');
    }

    private function generarCodigoUnico(string $nombre, TipoServicioModel $model, ?int $idExcluir = null): string
    {
        $base = mb_strtoupper($nombre);
        $base = preg_replace('/[^A-Z0-9]+/u', '_', $base);
        $partes = array_filter(explode('_', $base), function ($p) {
            return $p !== '' && ! in_array($p, ['DE', 'DEL', 'LA', 'EL', 'Y'], true);
        });
        $base = implode('_', $partes);
        $base = substr($base, 0, 16);

        $codigo = $base;
        $sufijo = 2;

        $query = $model->where('codigo', $codigo);
        if ($idExcluir) {
            $query->where('id !=', $idExcluir);
        }

        while ($query->first()) {
            $codigo = $base . '_' . $sufijo;
            $sufijo++;
            $query = $model->where('codigo', $codigo);
            if ($idExcluir) {
                $query->where('id !=', $idExcluir);
            }
        }

        return $codigo;
    }
}