<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class DashboardController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->requiereLogin();
    }

    public function index()
    {
        return view('dashboard/index', [
            'nombre' => $_SESSION['nombre'] ?? null,
            'rol'    => $_SESSION['rol'] ?? null,
        ]);
    }
}