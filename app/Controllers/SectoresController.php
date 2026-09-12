<?php

namespace App\Controllers;

use App\Models\SectorModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class SectoresController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereRol(['administrador']);
    }

    public function index()
    {
        $model = new SectorModel();

        return view('sectores/index', [
            'titulo'   => 'Sectores',
            'sectores' => $model->orderBy('nombre', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('sectores/create', ['titulo' => 'Nuevo Sector']);
    }

    public function store()
    {
        $model = new SectorModel();

        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
        ];

        if (! $model->validate($data)) {
            return view('sectores/create', [
                'titulo' => 'Nuevo Sector',
                'errors' => $model->errors(),
                'old'    => $data,
            ]);
        }

        $model->insert($data);

        flash_set('message', 'Sector creado correctamente.');
        return redirect()->to(base_url('sectores'));
    }

    public function edit($id)
    {
        $model  = new SectorModel();
        $sector = $model->find($id);

        if (! $sector) {
            flash_set('error', 'Sector no encontrado.');
            return redirect()->to(base_url('sectores'));
        }

        return view('sectores/edit', [
            'titulo' => 'Editar Sector',
            'sector' => $sector,
        ]);
    }

    public function update($id)
    {
        $model  = new SectorModel();
        $sector = $model->find($id);

        if (! $sector) {
            flash_set('error', 'Sector no encontrado.');
            return redirect()->to(base_url('sectores'));
        }

        $data = [
            'nombre' => trim((string) $this->request->getPost('nombre')),
        ];

        if (! $model->validate(array_merge(['id' => $id], $data))) {
            return view('sectores/edit', [
                'titulo' => 'Editar Sector',
                'sector' => array_merge($sector, $data),
                'errors' => $model->errors(),
            ]);
        }

        $model->update($id, $data);

        flash_set('message', 'Sector actualizado correctamente.');
        return redirect()->to(base_url('sectores'));
    }

    public function delete($id)
    {
        $model = new SectorModel();

        $enContadores = db_connect()->table('Tb_Contadores')->where('sector_id', $id)->countAllResults();

        if ($enContadores > 0) {
            flash_set('error', 'No se puede eliminar: este sector tiene contadores asociados.');
            return redirect()->to(base_url('sectores'));
        }

        $model->delete($id);

        flash_set('message', 'Sector eliminado.');
        return redirect()->to(base_url('sectores'));
    }
}