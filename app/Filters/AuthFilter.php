<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Exige que haya un usuario con sesion iniciada.
 * Si no la hay, redirige al login.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (! $session->get('usuario_id')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesion para continuar.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada que hacer despues de la respuesta.
    }
}
